<?php

namespace Application;

use Symfony\Component\HttpFoundation\Response;

abstract class BaseModule extends \OU\ModuleAbstract
{

    /**
     * @return mixed
     */
    abstract public function internalServerError();

    /**
     * @param $controllerClass
     * @param string $requestMethod
     * @param array $params
     * @return mixed|void
     */
    public function dispatch($controllerClass, $requestMethod = 'get', array $params = array())
    {
        $this->setErrorHandler();

        /**
         * @var BaseController $controller
         */
        $controller = new $controllerClass($this);
        $controller->setServiceContainer(new ServiceContainer($this->getApplication()->getConfigs()));

        /**
         * @var Response $response
         */
        $response = call_user_func_array(array($controller, $requestMethod), $params);
        $response->send();
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
        error_log($exception->getMessage() . ' ' . $exception->getTraceAsString());
        $this->internalServerError();
    }
}
