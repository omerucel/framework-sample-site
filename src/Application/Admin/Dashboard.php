<?php

namespace Application\Admin;

class Dashboard extends BaseAdminController
{
    public function get()
    {
        return $this->render('admin/dashboard.twig');
    }
}
