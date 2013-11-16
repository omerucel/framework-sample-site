<?php

namespace Application\Site;

use Application\BaseController;
use Application\Site;

abstract class BaseSiteController extends BaseController
{
    /**
     * @var Site
     */
    protected $module;

    /**
     * @param Site $module
     */
    public function __construct(Site $module)
    {
        $this->module = $module;
    }

    /**
     * @return Site
     */
    public function getModule()
    {
        return $this->module;
    }
}
