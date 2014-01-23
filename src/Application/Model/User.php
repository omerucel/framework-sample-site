<?php

namespace Application\Model;

class User extends BaseModel
{
    /**
     * @var int
     */
    public $id = 0;

    /**
     * @var string
     */
    public $username = '';

    /**
     * @var string
     */
    public $password = '';

    /**
     * @var bool
     */
    protected $isAdminUser;

    /**
     * @return bool
     */
    public function isAdminUser()
    {
        if ($this->isAdminUser === null) {
            $this->isAdminUser = $this->getServiceContainer()
                ->getMapperContainer()
                ->getUserMapper()
                ->isAdminUser($this->id);
        }

        return $this->isAdminUser;
    }

    /**
     * @return void
     */
    public function insertNewLoginHistory()
    {
        $this->getServiceContainer()
            ->getMapperContainer()
            ->getUserMapper()
            ->newLoginHistory($this->id);
    }
}
