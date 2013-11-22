<?php

namespace Application\Database;

use Application\ServiceContainer;

abstract class BaseMapper
{
    /**
     * @var ServiceContainer
     */
    protected $serviceContainer;

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @return ServiceContainer
     */
    public function getServiceContainer()
    {
        return $this->serviceContainer;
    }
}
