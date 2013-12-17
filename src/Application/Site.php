<?php

namespace Application;

use Symfony\Component\HttpFoundation\Response;

class Site extends BaseModule
{
    /**
     * @return mixed|void
     */
    public function internalServerError()
    {
        $this->dispatch('Application\Site\InternalError');
        exit;
    }

    public function pageNotFound()
    {
        $this->dispatch('Application\Site\NotFound');
        exit;
    }
}
