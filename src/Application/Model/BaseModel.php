<?php

namespace Application\Model;

use Application\ServiceContainer;
use Application\Utility;

abstract class BaseModel
{
    /**
     * @var ServiceContainer
     */
    protected $serviceContainer;

    /**
     * @param ServiceContainer $serviceContainer
     */
    public function setServiceContainer($serviceContainer)
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

    /**
     * @return string
     */
    public function serialize()
    {
        $data = new \stdClass();
        foreach (Utility::getPublicVars($this) as $key => $value) {
            $data->{$key} = $value;
        }

        return serialize($data);
    }

    /**
     * @param string $data
     */
    public function unserialize($data)
    {
        $data = unserialize($data);
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
