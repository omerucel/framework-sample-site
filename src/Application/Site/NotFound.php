<?php

namespace Application\Site;

class NotFound extends BaseSiteController
{
    public function get()
    {
        return $this->render('site/404.twig');
    }
}
