<?php

namespace Application;

use OU\Application;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseModule extends \OU\ModuleAbstract
{

    /**
     * @return mixed
     */
    abstract public function internalServerError();

    /**
     * @var ServiceContainer
     */
    protected $serviceContainer;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        parent::__construct($application);

        $this->setErrorHandler();
    }

    /**
     * @return ServiceContainer
     */
    public function getServiceContainer()
    {
        if ($this->serviceContainer == null) {
            $this->serviceContainer = new ServiceContainer($this->getApplication()->getConfigs());
        }

        return $this->serviceContainer;
    }

    /**
     * @param $controllerClass
     * @param string $requestMethod
     * @param array $params
     * @return mixed|void
     */
    public function dispatch($controllerClass, $requestMethod = 'get', array $params = array())
    {
        /**
         * @var BaseController $controller
         */
        $controller = new $controllerClass($this);
        $controller->setServiceContainer($this->getServiceContainer());

        /**
         * @var Response $response
         */

        $response = $controller->preDispatch();
        if (!$response instanceof Response) {
            $response = call_user_func_array(array($controller, $requestMethod), $params);

            $postDispatchResponse = $controller->postDispatch();
            if ($postDispatchResponse instanceof Response) {
                $response = $postDispatchResponse;
            }

            if (!$response instanceof Response) {
                $response = $this->getServiceContainer()->getResponse();
            }
        }

        $response->send();
    }

    public function setErrorHandler()
    {
        set_error_handler(array($this, 'errorHandler'));
        set_exception_handler(array($this, 'exceptionHandler'));
        register_shutdown_function(array($this, 'fatalErrorHandler'));
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
        $message = $exception->getMessage() . ' ' . $exception->getTraceAsString();
        $message = str_replace("\n", '', $message);

        $this->getServiceContainer()->getMonolog()->error($message);
        $this->internalServerError();
    }

    public function fatalErrorHandler()
    {
        $error = error_get_last();

        if( $error !== NULL) {
            $errNo = $error["type"];
            $errFile = $error["file"];
            $errLine = $error["line"];
            $errStr = $error["message"];

            $message = $errNo . ' ' . $errStr . ' ' . $errFile . ':' . $errLine;
            $message = str_replace("\n", '', $message);

            $this->getServiceContainer()->getMonolog()->error($message);
            $this->internalServerError();
        }
    }
}
