<?php

namespace Application\Admin;

class Logout extends BaseAdminController
{
    public function get()
    {
        $this->getServiceContainer()->getSession()->clear();
        return $this->redirect('/admin');
    }
}