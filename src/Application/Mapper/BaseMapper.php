<?php

namespace Application\Mapper;

use Application\Model\BaseModel;
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

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function createQueryBuilder()
    {
        return $this->getServiceContainer()->getConnection()->createQueryBuilder();
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection()
    {
        return $this->getServiceContainer()->getConnection();
    }

    /**
     * @param $data
     * @param $class
     * @return BaseModel|null
     */
    public function loadObject($data, $class)
    {
        if ($data == null) {
            return $data;
        }

        /**
         * @var BaseModel $object
         */
        $object = new $class();
        $object->setServiceContainer($this->getServiceContainer());

        foreach ($data as $key => $value) {
            $object->{$key} = $value;
        }

        return $object;
    }

    /**
     * @param array $items
     * @param $class
     * @return array
     */
    public function loadObjects(array $items, $class)
    {
        $objects = array();
        foreach ($items as $item) {
            $objects[] = $this->loadObject($item, $class);
        }

        return $objects;
    }
}
