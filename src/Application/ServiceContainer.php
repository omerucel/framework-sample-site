<?php

namespace Application;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

class ServiceContainer
{
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
        $this->configs = $configs;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse()
    {
        if (!isset($this->services[__METHOD__])) {
            $this->services[__METHOD__] = new \Symfony\Component\HttpFoundation\Response();
        }

        return $this->services[__METHOD__];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        if (!isset($this->services[__METHOD__])) {
            $this->services[__METHOD__] = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
        }

        return $this->services[__METHOD__];
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        if (!isset($this->services[__METHOD__])) {
            $loader = new \Twig_Loader_Filesystem($this->configs['twig']['template_path']);
            $this->services[__METHOD__] = new \Twig_Environment($loader, $this->configs['twig']['options']);
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
     * @return \Monolog\Logger
     */
    public function getMonolog()
    {
        if (!isset($this->services[__METHOD__])) {
            $formatter = new \Monolog\Formatter\LineFormatter(
                $this->configs['monolog']['line_format'],
                $this->configs['monolog']['datetime_format']
            );

            $stream = new \Monolog\Handler\StreamHandler(
                $this->configs['monolog']['file'],
                $this->configs['monolog']['level']
            );
            $stream->setFormatter($formatter);

            $monolog = new \Monolog\Logger($this->configs['monolog']['name']);
            $monolog->pushHandler($stream);

            $this->services[__METHOD__] = $monolog;
        }

        return $this->services[__METHOD__];
    }
}
