<?php

namespace Application;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class Admin extends BaseModule
{
    /**
     * @return Response
     */
    public function internalServerError()
    {
        return new RedirectResponse('/admin/500');
    }
}
