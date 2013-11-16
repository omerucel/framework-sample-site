<?php

namespace Application;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class Site extends BaseModule
{
    /**
     * @return mixed|void
     */
    public function internalServerError()
    {
        $response = new RedirectResponse('/500');
        $response->send();
    }
}
