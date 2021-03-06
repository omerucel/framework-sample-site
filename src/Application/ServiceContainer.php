<?php

namespace Application;

use Application\Mapper\MapperContainer;
use Captcha\Captcha;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

class ServiceContainer
{
    /**
     * @var ServiceContainer
     */
    private static $instance;

    /**
     * @var array
     */
    protected $configs = array();

    /**
     * @var array
     */
    protected $services = array();

    /**
     * @param array $configs
     */
    public function __construct(array $configs = array())
    {
        AnnotationRegistry::registerAutoloadNamespaces(array(
            'Symfony\Component\Validator\Constraints' => $configs['base_path'] . '/vendor/symfony/validator',
            'Application' => $configs['base_path'] . '/src/'
        ));

        $this->configs = $configs;
    }

    /**
     * @return ServiceContainer
     */
    public static function getInstance()
    {
        if (static::$instance == null) {
            static::$instance = new ServiceContainer();
        }

        return static::$instance;
    }

    /**
     * @param array $configs
     */
    public function setConfigs($configs)
    {
        $this->configs = $configs;
    }

    /**
     * @return array
     */
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        if (!isset($this->services[__METHOD__])) {
            $this->services[__METHOD__] = new Response();
        }

        return $this->services[__METHOD__];
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if (!isset($this->services[__METHOD__])) {
            $this->services[__METHOD__] = Request::createFromGlobals();
        }

        return $this->services[__METHOD__];
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        if (!isset($this->services[__METHOD__])) {
            $configs = $this->getConfigs();
            $loader = new \Twig_Loader_Filesystem($this->configs['twig']['template_path']);
            $twig = new TwigEnvironment($loader, $this->configs['twig']['options']);

            $checkPath = function ($arguments) {
                $path = array_shift($arguments);
                $path = vsprintf($path, $arguments);
                if ($path[0] == '/') {
                    $path = substr($path, 1);
                }

                return $path;
            };

            $asset = new \Twig_SimpleFunction(
                'asset',
                function () use ($configs, $checkPath) {
                    $arguments = func_get_args();
                    $path = $checkPath($arguments);

                    return $configs['asset_url'] . $path;
                }
            );

            $siteUrl = new \Twig_SimpleFunction(
                'site_url',
                function () use ($configs, $checkPath) {
                    $arguments = func_get_args();
                    $path = $checkPath($arguments);

                    return $configs['site_url'] . $path;
                }
            );

            $mediaUrl = new \Twig_SimpleFunction(
                'media_url',
                function () use ($configs, $checkPath) {
                    $arguments = func_get_args();
                    $path = $checkPath($arguments);

                    return $configs['media_url'] . $path;
                }
            );

            $twig->addFunction('asset', $asset);
            $twig->addFunction('site_url', $siteUrl);
            $twig->addFunction('media_url', $mediaUrl);

            $this->services[__METHOD__] = $twig;
        }

        return $this->services[__METHOD__];
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        if (!isset($this->services[__METHOD__])) {
            $pdoHandler = new PdoSessionHandler($this->getPdo(), $this->configs['session']);
            $storage = new NativeSessionStorage(array(), $pdoHandler);
            $storage->setOptions($this->configs['session']);

            $session = new Session($storage);
            $session->start();

            $this->services[__METHOD__] = $session;
        }

        return $this->services[__METHOD__];
    }

    /**
     * @return \PDO
     */
    public function getPDO()
    {
        if (!isset($this->services[__METHOD__])) {
            $pdo = new \PDO(
                $this->configs['pdo']['dsn'],
                $this->configs['pdo']['username'],
                $this->configs['pdo']['password']
            );
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            $this->services[__METHOD__] = $pdo;
        }

        return $this->services[__METHOD__];
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        if (!isset($this->services[__METHOD__])) {
            $config = new Configuration();
            $conn = DriverManager::getConnection(array('pdo' => $this->getPDO()), $config);
            $this->services[__METHOD__] = $conn;
        }

        return $this->services[__METHOD__];
    }

    /**
     * @return MapperContainer
     */
    public function getMapperContainer()
    {
        if (!isset($this->services[__METHOD__])) {
            $this->services[__METHOD__] = new MapperContainer($this);
        }

        return $this->services[__METHOD__];
    }

    /**
     * @return \Monolog\Logger
     */
    public function getMonolog()
    {
        if (!isset($this->services[__METHOD__])) {
            $formatter = new LineFormatter(
                $this->configs['monolog']['line_format'],
                $this->configs['monolog']['datetime_format']
            );

            $stream = new StreamHandler(
                $this->configs['monolog']['file'],
                $this->configs['monolog']['level']
            );
            $stream->setFormatter($formatter);

            $monolog = new Logger($this->configs['monolog']['name']);
            $monolog->pushHandler($stream);

            $this->services[__METHOD__] = $monolog;
        }

        return $this->services[__METHOD__];
    }

    /**
     * @return \Swift_Mailer
     */
    public function getSwiftMailer()
    {
        if (!isset($this->services[__METHOD__])) {
            $transport = \Swift_SmtpTransport::newInstance();
            $transport->setUsername($this->configs['swiftmailer']['username']);
            $transport->setPassword($this->configs['swiftmailer']['password']);
            $transport->setHost($this->configs['swiftmailer']['host']);
            $transport->setPort($this->configs['swiftmailer']['port']);

            $this->services[__METHOD__] = \Swift_Mailer::newInstance($transport);
        }

        return $this->services[__METHOD__];
    }

    /**
     * @return \Facebook
     */
    public function getFacebook()
    {
        if (!isset($this->services[__METHOD__])) {
            $this->services[__METHOD__] = new \Facebook(
                array(
                    'appId' => $this->configs['facebook']['app_id'],
                    'secret' => $this->configs['facebook']['app_secret']
                )
            );
        }

        return $this->services[__METHOD__];
    }

    /**
     * @return Captcha
     */
    public function getCaptcha()
    {
        if (!isset($this->services[__METHOD__])) {
            $captcha = new Captcha();
            $captcha->setPublicKey($this->configs['recaptcha']['public_key']);
            $captcha->setPrivateKey($this->configs['recaptcha']['private_key']);

            $this->services[__METHOD__] = $captcha;
        }

        return $this->services[__METHOD__];
    }

    /**
     * @return \Doctrine\Common\Annotations\AnnotationReader
     */
    public function getAnnotationReader()
    {
        if (!isset($this->services[__METHOD__])) {
            $this->services[__METHOD__] = new \Doctrine\Common\Annotations\AnnotationReader();
        }

        return $this->services[__METHOD__];
    }
}
