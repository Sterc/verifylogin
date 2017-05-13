<?php /** @noinspection AutoloadingIssuesInspection */

/**
 * The main VerifyLogin class.
 *
 * @package verifylogin
 */

class VerifyLogin
{
    /**
     * Holds the MODX object.
     */
    public $modx;

    /**
     * Holds the namespace.
     */
    public $namespace;

    /**
     * VerifyLogin constructor.
     *
     * @param modX  $modx
     * @param array $options
     */
    public function __construct(modX $modx, array $options = [])
    {
        $this->modx      =& $modx;
        $this->namespace = $this->getOption('namespace', $options, 'verifylogin');

        $corePath = $this->getOption(
            'core_path',
            $options,
            $this->modx->getOption(
                'core_path',
                null,
                MODX_CORE_PATH
            ) . 'components/verifylogin/'
        );

        $assetsPath = $this->getOption(
            'assets_path',
            $options,
            $this->modx->getOption(
                'assets_path',
                null,
                MODX_ASSETS_PATH
            ) . 'components/verifylogin/'
        );

        $assetsUrl = $this->getOption(
            'assets_url',
            $options,
            $this->modx->getOption(
                'assets_url',
                null,
                MODX_ASSETS_URL
            ) . 'components/verifylogin/'
        );

        /*
         * Loads some default paths for easier management.
         */
        $this->options = array_merge(
            array(
                'namespace'         => $this->namespace,
                'corePath'          => $corePath,
                'modelPath'         => $corePath . 'model/',
                'chunksPath'        => $corePath . 'elements/chunks/',
                'snippetsPath'      => $corePath . 'elements/snippets/',
                'templatesPath'     => $corePath . 'templates/',
                'assetsPath'        => $assetsPath,
                'assetsUrl'         => $assetsUrl,
                'jsUrl'             => $assetsUrl . 'js/',
                'cssUrl'            => $assetsUrl . 'css/',
                'connectorUrl'      => $assetsUrl . 'connector.php',
            ),
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
        if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
            require_once __DIR__ . '/../../vendor/autoload.php';

            if (isset($_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR'])) {
                $browserInfo = new WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
                $record = array(
                    'user_id' => $user->get('id'),
                    'ip_address' => ip2long($_SERVER['REMOTE_ADDR']),
                    'user_agent' => serialize(array(
                        'browser'     => $browserInfo->browser->name,
                        'os'          => $browserInfo->os->name,
                        'device_type' => $browserInfo->device->type,
                    ))
                );

                if (!$this->modx->getObject('verifyLoginRecord', $record)) {
                    $object = $this->modx->newObject('verifyLoginRecord');
                    $object->fromArray($record);
                    $object->save();

                    $profile = $user->getOne('Profile');
                    if ($profile && !empty($profile->get('email'))) {
                        $language = $this->modx->getOption('manager_language');
                        $this->modx->lexicon->load($language . ':verifylogin:default');
                        $userAgent = unserialize($record['user_agent']);

                        $parameters = array(
                            'topic'       => 'default',
                            'namespace'   => $this->namespace,
                            'username'    => $user->get('username'),
                            'fullname'    => $profile->get('fullname'),
                            'email'       => $profile->get('email'),
                            'date'        => date($this->modx->getOption('manager_date_format') . ' H:i:s'),
                            'ip_address'  => long2ip($record['ip_address']),
                            'browser'     => $userAgent['browser'],
                            'os'          => $userAgent['os'],
                            'device_type' => $userAgent['device_type'],
                            'language'    => $language,
                            'site_name'   => $this->modx->getOption('site_name'),
                            'manager_url' => rtrim(MODX_SITE_URL, '/') . MODX_MANAGER_URL,
                        );

                        $subject = $this->modx->lexicon('verifylogin.mail.subject', $parameters, $language);
                        $mailHtml = $this->modx->getChunk('verifyLogin', $parameters);

                        $this->modx->getService('mail', 'mail.modPHPMailer');
                        $this->modx->mail->set(modMail::MAIL_BODY, $mailHtml);
                        $this->modx->mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
                        $this->modx->mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
                        $this->modx->mail->set(modMail::MAIL_SUBJECT, $subject);
                        $this->modx->mail->address('to', $profile->get('email'));
                        $this->modx->mail->address('reply-to', $this->modx->getOption('emailsender'));
                        $this->modx->mail->setHTML(true);
                        $this->modx->mail->send();
                        $this->modx->mail->reset();
                    }
                }
            }
        }

        return true;
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, array $options = array(), $default = null)
    {
        $option = $default;

        if (!empty($key) && is_string($key)) {
            if ($options !== null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (is_array($this->options) && array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }

        return $option;
    }
}
