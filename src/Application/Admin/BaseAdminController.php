<?php

namespace Application\Admin;

use Application\BaseController;
use Application\Admin;

abstract class BaseAdminController extends BaseController
{
    /**
     * @var Admin
     */
    protected $module;

    /**
     * @param Admin $module
     */
    public function __construct(Admin $module)
    {
        $this->module = $module;
    }

    /**
     * @return Admin
     */
    public function getModule()
    {
        return $this->module;
    }
}
