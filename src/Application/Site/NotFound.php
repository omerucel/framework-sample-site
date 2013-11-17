<?php

namespace Application\Site;

class NotFound extends BaseSiteController
{
    /**
     * @param $name
     * @param $arguments
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __call($name, $arguments)
    {
        $logger = $this->getServiceContainer()->getMonolog();
        $request = $this->getServiceContainer()->getRequest();

        $logger->info(__METHOD__ . ' ' . $name . ' ' . $request->getRequestUri());
        return $this->render('site/404.twig');
    }
}
