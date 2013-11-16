<?php

namespace Application\Admin;

class InternalError extends BaseAdminController
{
    public function get()
    {
        return $this->render('admin/500.twig', array(), 500);
    }
}
