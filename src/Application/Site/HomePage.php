<?php

namespace Application\Site;

class HomePage extends BaseSiteController
{
    public function get()
    {
        return $this->render('site/index.twig');
    }
}
