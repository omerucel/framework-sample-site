<?php

namespace Application\Database;

use Application\ServiceContainer;

class MapperContainer
{
    /**
     * @var ServiceContainer
     */
    protected $serviceContainer;

    /**
     * @var array
     */
    protected $mappers = array();

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @return \Application\ServiceContainer
     */
    public function getServiceContainer()
    {
        return $this->serviceContainer;
    }

    /**
     * @return UserMapper
     */
    public function getUserMapper()
    {
        if (!isset($this->mappers[__METHOD__])) {
            $this->mappers[__METHOD__] = new UserMapper($this->getServiceContainer());
        }

        return $this->mappers[__METHOD__];
    }
}
