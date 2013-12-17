<?php

namespace Application\Service;

use Application\ServiceContainer;

abstract class BaseService
{
    /**
     * @var array
     */
    protected $configs;

    /**
     * @var ServiceContainer
     */
    protected $serviceContainer;

    /**
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        $this->configs = $configs;
        $this->setErrorHandler();
    }

    public function setErrorHandler()
    {
        set_error_handler(array($this, 'errorHandler'));
        set_exception_handler(array($this, 'exceptionHandler'));
    }

    /**
     * Bir hata oluştuğunda bu metod tetiklenir.
     *
     * @param $errNo
     * @param $errStr
     * @param $errFile
     * @param $errLine
     * @throws \ErrorException
     */
    public function errorHandler($errNo, $errStr, $errFile, $errLine)
    {
        throw new \ErrorException($errStr, $errNo, 0, $errFile, $errLine);
    }

    /**
     * Bir exception fırlatıldığında bu metod tetiklenir.
     *
     * @param \Exception $exception Fırlatılan \Exception sınıfı.
     * @return void
     */
    public function exceptionHandler(\Exception $exception)
    {
        $message = $exception->getMessage() . ' '
            . $exception->getTraceAsString();
        $message = str_replace("\n", '', $message);

        $this->getServiceContainer()->getMonolog()->error($message);
    }

    /**
     * @return ServiceContainer
     */
    public function getServiceContainer()
    {
        if ($this->serviceContainer == null) {
            $this->serviceContainer = new ServiceContainer($this->configs);
        }

        return $this->serviceContainer;
    }
}
