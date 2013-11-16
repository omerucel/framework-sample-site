<?php

namespace Application;

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
}
