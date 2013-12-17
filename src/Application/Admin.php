<?php

namespace Application;

use Symfony\Component\HttpFoundation\Response;

class Admin extends BaseModule
{
    /**
     * @return mixed|void
     */
    public function internalServerError()
    {
        $this->dispatch('Application\Site\InternalError');
        exit;
    }
}
