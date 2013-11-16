<?php

namespace Application;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class Admin extends BaseModule
{
    /**
     * @return mixed|void
     */
    public function internalServerError()
    {
        $response = new RedirectResponse('/admin/500');
        $response->send();
    }
}
