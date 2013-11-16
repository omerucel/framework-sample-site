<?php

namespace Application;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class Site extends BaseModule
{
    /**
     * @return Response
     */
    public function internalServerError()
    {
        return new RedirectResponse('/500');
    }
}
