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

                    $userSettings = $user->getSettings();

                    $profile = $user->getOne('Profile');
                    if ($profile && !empty($profile->get('email'))) {
                        $dateFormat = $this->getOption(
                            'email_date_format',
                            array(),
                            $this->modx->getOption('manager_date_format') . ' H:i:s'
                        );
                        $closure = $this->getOption('email_closure', array(), $this->modx->getOption('site_name'));
                        $emailChunk = $this->getOption('email_chunk', array(), 'verifyLogin');
                        $language = isset($userSettings['manager_language']) ? $userSettings['manager_language'] :
                            $this->modx->getOption('manager_language');
                        $userAgent = unserialize($record['user_agent']);
                        $this->modx->lexicon->load($language . ':verifylogin:default');

                        /**
                         * Set additional content.
                         */
                        $additionalContentJson = json_decode($this->getOption('email_additional_content'), true);
                        $additionalContent = '';
                        if (is_array($additionalContentJson) && isset($additionalContentJson[$language])) {
                            $additionalContent = $additionalContentJson[$language];
                        }

                        $parameters = array(
                            'topic'       => 'default',
                            'namespace'   => $this->namespace,
                            'username'    => $user->get('username'),
                            'fullname'    => $profile->get('fullname'),
                            'email'       => $profile->get('email'),
                            'date'        => date($dateFormat),
                            'ip_address'  => long2ip($record['ip_address']),
                            'browser'     => $userAgent['browser'],
                            'os'          => $userAgent['os'],
                            'device_type' => $userAgent['device_type'],
                            'language'    => $language,
                            'site_name'   => $this->modx->getOption('site_name'),
                            'closure'     => $closure,
                            'additional'  => $additionalContent,
                            'assets_url'  => $this->getOption('assetsUrl'),
                            'site_url'    => rtrim(MODX_SITE_URL, '/'),
                            'manager_url' => rtrim(MODX_SITE_URL, '/') . MODX_MANAGER_URL . '?a=security/profile',
                        );

                        $subject = $this->modx->lexicon('verifylogin.mail.subject', $parameters, $language);
                        $mailHtml = $this->modx->getChunk($emailChunk, $parameters);

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

    public function f() {
        // Only run if we're in the manager
        if (!$this->modx->context || $this->modx->context->get('key') !== 'mgr') {
            return;
        }

        $c = $this->modx->newQuery('transport.modTransportPackage', array('package_name' => __CLASS__));
        $c->innerJoin('transport.modTransportProvider', 'modTransportProvider', 'modTransportProvider.id = modTransportPackage.provider');
        $c->select('modTransportProvider.service_url');
        $c->sortby('modTransportPackage.created', 'desc');
        $c->limit(1);
        if ($c->prepare() && $c->stmt->execute()) {
            $url = $c->stmt->fetchColumn();
            if (stripos($url, 'modstore')) {
                $this->ms();
                return;
            }
        }

        $this->mm();
    }

    protected function ms() {
        $result = true;
        $key = strtolower(__CLASS__);
        /** @var modDbRegister $registry */
        $registry = $this->modx->getService('registry', 'registry.modRegistry')
            ->getRegister('user', 'registry.modDbRegister');
        $registry->connect();
        $registry->subscribe('/modstore/' . md5($key));
        if ($res = $registry->read(array('poll_limit' => 1, 'remove_read' => false))) {
            return $res[0];
        }
        $c = $this->modx->newQuery('transport.modTransportProvider', array('service_url:LIKE' => '%modstore%'));
        $c->select('username,api_key');
        /** @var modRest $rest */
        $rest = $this->modx->getService('modRest', 'rest.modRest', '', array(
            'baseUrl' => 'https://modstore.pro/extras',
            'suppressSuffix' => true,
            'timeout' => 1,
            'connectTimeout' => 1,
            'format' => 'xml',
        ));

        if ($rest) {
            $level = $this->modx->getLogLevel();
            $this->modx->setLogLevel(modX::LOG_LEVEL_FATAL);
            /** @var RestClientResponse $response */
            $response = $rest->get('stat', array(
                'package' => $key,
                'host' => @$_SERVER['HTTP_HOST'],
                'keys' => $c->prepare() && $c->stmt->execute()
                    ? $c->stmt->fetchAll(PDO::FETCH_ASSOC)
                    : array(),
            ));
            $result = $response->process() == 'true';
            $this->modx->setLogLevel($level);
        }
        $registry->subscribe('/modstore/');
        $registry->send('/modstore/', array(md5($key) => $result), array('ttl' => 3600 * 24));

        return $result;
    }

    protected function mm() {
        // Get the public key from the .pubkey file contained in the package directory
        $pubKeyFile = $this->options['corePath'] . '.pubkey';
        $key = file_exists($pubKeyFile) ? file_get_contents($pubKeyFile) : '';
        $domain = $this->modx->getOption('http_host');
        if (strpos($key, '@@') !== false) {
            $pos = strpos($key, '@@');
            $domain = substr($key, 0, $pos);
            $key = substr($key, $pos + 2);
        }
        $check = false;
        // No key? That's a really good reason to check :)
        if (empty($key)) {
            $check = true;
        }
        // Doesn't the domain in the key file match the current host? Then we should get that sorted out.
        if ($domain !== $this->modx->getOption('http_host')) {
            $check = true;
        }
        // the .pubkey_c file contains a unix timestamp saying when the pubkey was last checked
        $modified = file_exists($pubKeyFile . '_c') ? file_get_contents($pubKeyFile . '_c') : false;
        if (!$modified ||
            $modified < (time() - (60 * 60 * 24 * 7)) ||
            $modified > time()) {
            $check = true;
        }
        if ($check) {
            $provider = false;
            $c = $this->modx->newQuery('transport.modTransportPackage');
            $c->where(array(
                'signature:LIKE' => 'formalicious-%',
            ));
            $c->sortby('installed', 'DESC');
            $c->limit(1);
            $package = $this->modx->getObject('transport.modTransportPackage', $c);
            if ($package instanceof modTransportPackage) {
                $provider = $package->getOne('Provider');
            }
            if (!$provider) {
                $provider = $this->modx->getObject('transport.modTransportProvider', array(
                    'service_url' => 'https://rest.modmore.com/'
                ));
            }
            if ($provider instanceof modTransportProvider) {
                $this->modx->setOption('contentType', 'default');
                // The params that get sent to the provider for verification
                $params = array(
                    'key' => $key,
                    'package' => strtolower(__CLASS__),
                );
                // Fire it off and see what it gets back from the XML..
                $response = $provider->request('license', 'GET', $params);
                $xml = $response->toXml();
                $valid = (int)$xml->valid;
                // If the key is found to be valid, set the status to true
                if ($valid) {
                    // It's possible we've been given a new public key (typically for dev licenses or when user has unlimited)
                    // which we will want to update in the pubkey file.
                    $updatePublicKey = (bool)$xml->update_pubkey;
                    if ($updatePublicKey > 0) {
                        file_put_contents($pubKeyFile,
                            $this->modx->getOption('http_host') . '@@' . (string)$xml->pubkey);
                    }
                    file_put_contents($pubKeyFile . '_c', time());
                    return;
                }

                $message = (string)$xml->message;
                $url = (string)$xml->case_url;
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[LICENSE ALERT] The ' . __CLASS__ . ' license on this site is invalid. Please visit ' . $url . ' to correct the license issue. Description: ' . $message);
            }
            else {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'UNABLE TO VERIFY MODMORE LICENSE - PROVIDER NOT FOUND!');
            }
        }
    }
}
