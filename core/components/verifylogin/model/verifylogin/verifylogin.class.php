<?php

/** @noinspection AutoloadingIssuesInspection */

/**
 * The main VerifyLogin class.
 */
class VerifyLogin
{
    /**
     * Holds path to component.
     */
    const COMPONENT_PATH = 'components/verifylogin/';

    /**
     * Holds the MODX object.
     */
    public $modx;

    /**
     * Holds the namespace.
     */
    public $namespace;

    /**
     * Holds browser info.
     */
    private $browserInfo;

    /**
     * Holds language.
     */
    private $language;

    /**
     * Holds date format.
     */
    private $dateFormat;

    /**
     * Holds ignore email list.
     */
    private $emailIgnoreList;

    /**
     * Holds user.
     */
    private $user;

    /**
     * Holds user profile.
     */
    private $userProfile;

    /**
     * VerifyLogin constructor.
     */
    public function __construct(modX $modx, array $options = [])
    {
        $this->modx = &$modx;
        $this->namespace = $this->getOption('namespace', $options, 'verifylogin');
        $this->dateFormat = $this->getOption('email_date_format', [], $this->modx->getOption('manager_date_format').' H:i:s');
        $this->emailIgnoreList = $this->getOption('email_ignore_list', [], '');

        $corePath = $this->getOption(
            'core_path',
            $options,
            $this->modx->getOption(
                'core_path',
                null,
                MODX_CORE_PATH
            ).self::COMPONENT_PATH
        );

        $assetsPath = $this->getOption(
            'assets_path',
            $options,
            $this->modx->getOption(
                'assets_path',
                null,
                MODX_ASSETS_PATH
            ).self::COMPONENT_PATH
        );

        $assetsUrl = $this->getOption(
            'assets_url',
            $options,
            $this->modx->getOption(
                'assets_url',
                null,
                MODX_ASSETS_URL
            ).self::COMPONENT_PATH
        );

        // Loads some default paths for easier management.
        $this->options = array_merge(
            [
                'namespace' => $this->namespace,
                'corePath' => $corePath,
                'modelPath' => $corePath.'model/',
                'chunksPath' => $corePath.'elements/chunks/',
                'snippetsPath' => $corePath.'elements/snippets/',
                'templatesPath' => $corePath.'templates/',
                'assetsPath' => $assetsPath,
                'assetsUrl' => $assetsUrl,
                'jsUrl' => $assetsUrl.'js/',
                'cssUrl' => $assetsUrl.'css/',
                'connectorUrl' => $assetsUrl.'connector.php',
            ],
            $options
        );

        $this->modx->addPackage('verifylogin', $this->getOption('modelPath'));
        $this->modx->lexicon->load('verifylogin:default');
    }

    /**
     * @param modUser $user The logged in user.
     *
     * Monitor the login action.
     *
     * @return bool
     */
    public function loginAction(modUser $user)
    {
        $this->user = &$user;
        $this->setUserLanguage();

        if (file_exists(__DIR__.'/../../vendor/autoload.php')) {
            require_once __DIR__.'/../../vendor/autoload.php';

            if (isset($_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'])) {
                $this->browserInfo = new WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
                $record = [
                    'user_id' => $this->user->get('id'),
                    'ip_address' => ip2long($_SERVER['REMOTE_ADDR']),
                    'user_agent' => serialize([
                        'browser' => $this->browserInfo->browser->name,
                        'os' => $this->browserInfo->os->name,
                        'device_type' => $this->browserInfo->device->type,
                    ]),
                ];

                if (!$this->modx->getObject('verifyLoginRecord', $record)) {
                    $object = $this->modx->newObject('verifyLoginRecord');
                    $object->fromArray($record);
                    $object->save();

                    $this->userProfile = $this->user->getOne('Profile');
                    if (
                        $this->userProfile
                        && !empty($this->userProfile->get('email'))
                        && !$this->avoidSendingEmail()
                    ) {
                        $parameters = [
                            'topic' => 'default',
                            'namespace' => $this->namespace,
                            'username' => $this->user->get('username'),
                            'fullname' => $this->userProfile->get('fullname'),
                            'email' => $this->userProfile->get('email'),
                            'date' => date($this->dateFormat),
                            'ip_address' => long2ip($record['ip_address']),
                            'browser' => $this->browserInfo->browser->name,
                            'os' => $this->browserInfo->os->name,
                            'device_type' => $this->browserInfo->device->type,
                            'language' => $this->language,
                            'site_name' => $this->modx->getOption('site_name'),
                            'closure' => $this->getOption('email_closure', [], $this->modx->getOption('site_name')),
                            'additional' => $this->additionalContent(),
                            'assets_url' => $this->getOption('assetsUrl'),
                            'site_url' => rtrim(MODX_SITE_URL, '/'),
                            'manager_url' => rtrim(MODX_SITE_URL, '/').MODX_MANAGER_URL.'?a=security/profile',
                        ];

                        $this->sendEmailNotification(
                            $this->modx->lexicon('verifylogin.mail.subject', $parameters, $this->language),
                            $this->modx->getChunk($this->getOption('email_chunk', [], 'verifyLogin'), $parameters),
                            $this->userProfile->get('email')
                        );
                    }
                }
            }
        }

        return true;
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key     The option key to search for.
     * @param array  $options An array of options that override local options.
     * @param mixed  $default The default value returned if the option is not found locally or as a
     *                        namespaced system setting; by default this value is null.
     *
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, array $options = [], $default = null)
    {
        $option = $default;

        if (!empty($key) && is_string($key)) {
            if (null !== $options && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (is_array($this->options) && array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }

        return $option;
    }

    /**
     * Return additional content.
     *
     * @return string The additional content to include
     */
    private function additionalContent()
    {
        $additionalJson = json_decode($this->getOption('email_additional_content'), true);

        return (is_array($additionalJson) && isset($additionalJson[$this->language]))
            ? $additionalJson[$this->language]
            : '';
    }

    /**
     * Set user language based on user configuration and load lexicon.
     */
    private function setUserLanguage()
    {
        $userSettings = $this->user->getSettings();
        $this->language = isset($userSettings['manager_language'])
            ? $userSettings['manager_language']
            : $this->modx->getOption('manager_language');
        $this->modx->lexicon->load($this->language.':verifylogin:default');
    }

    /**
     * Return if email should be sent or not based on email ignore list configuration.
     *
     * @return bool true if email should not be sent
     */
    private function avoidSendingEmail()
    {
        $ignoreList = explode(';', strtolower($this->emailIgnoreList));
        $email = strtolower($this->userProfile->get('email'));

        return in_array($email, $ignoreList);
    }

    /**
     * Send email with subject and body to recipient.
     *
     * @param string $subject   A subject of the email
     * @param string $body      A html content of message
     * @param string $recipient An email address of recipient
     */
    private function sendEmailNotification($subject, $body, $recipient)
    {
        $this->modx->getService('mail', 'mail.modPHPMailer');
        $this->modx->mail->set(modMail::MAIL_BODY, $body);
        $this->modx->mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
        $this->modx->mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
        $this->modx->mail->set(modMail::MAIL_SUBJECT, $subject);
        $this->modx->mail->address('to', $recipient);
        $this->modx->mail->address('reply-to', $this->modx->getOption('emailsender'));
        $this->modx->mail->setHTML(true);
        $this->modx->mail->send();
        $this->modx->mail->reset();
    }
}
