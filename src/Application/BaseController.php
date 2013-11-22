<?php

namespace Application;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController
{
    /**
     * @var ServiceContainer
     */
    protected $serviceContainer;

    /**
     * @return ServiceContainer
     */
    public function getServiceContainer()
    {
        return $this->serviceContainer;
    }

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function setServiceContainer(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @return null|Response
     */
    public function preDispatch()
    {
        return null;
    }

    /**
     * @return null|Response
     */
    public function postDispatch()
    {
        return null;
    }

    /**
     * @param $templateFile
     * @param array $templateVariables
     * @param int $status
     * @return Response
     */
    public function render($templateFile, $templateVariables = array(), $status = 200)
    {
        $output = $this->getServiceContainer()->getTwig()->render($templateFile, $templateVariables);
        return $this->toHtml($output, $status);
    }

    /**
     * @param array $result
     * @param int $status
     * @return Response
     */
    public function toJson(array $result, $status = 200)
    {
        $content = json_encode($result);
        return $this->createResponse($content, $status, 'application/json');
    }

    /**
     * @param $content
     * @param int $status
     * @return Response
     */
    public function toHtml($content, $status = 200)
    {
        return $this->createResponse($content, $status);
    }

    /**
     * @param $content
     * @param int $status
     * @return Response
     */
    public function toXml($content, $status = 200)
    {
        return $this->createResponse($content, $status, 'text/xml');
    }

    /**
     * @param $content
     * @param int $status
     * @return Response
     */
    public function toPlainText($content, $status = 200)
    {
        return $this->createResponse($content, $status, 'text/plain');
    }

    /**
     * @param $content
     * @param $status
     * @param string $contentType
     * @param string $charset
     * @return Response
     */
    public function createResponse($content, $status, $contentType = 'text/html', $charset = 'utf-8')
    {
        return $this->getServiceContainer()
            ->getResponse()
            ->create(
                $content,
                $status,
                array(
                    'content-type' => strtr(
                        ':contentType; charset=:charset',
                        array(
                            ':contentType' => $contentType,
                            ':charset' => $charset
                        )
                    )
                )
            );
    }

    /**
     * @param $url
     * @param int $code
     * @return RedirectResponse
     */
    public function redirect($url, $code = 302)
    {
        return new RedirectResponse($url, $code);
    }
}
