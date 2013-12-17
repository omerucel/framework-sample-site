<?php

namespace Application\Admin;

use Application\BaseController;
use Application\Admin;
use Application\Model\User;

abstract class BaseAdminController extends BaseController
{
    /**
     * @var Admin
     */
    protected $module;

    /**
     * @var User
     */
    protected $currentUser;

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

    public function preDispatch()
    {
        $status = $this->getServiceContainer()->getSession()->get('is_admin_logged_in');

        if (!$status
            && !$this instanceof Login
            && !$this instanceof InternalError) {
            return $this->redirect('/admin/login');
        }

        if (!$this instanceof Login && !$this instanceof InternalError) {
            $userData = $this->getServiceContainer()->getSession()->get('admin_user_data');

            $user = new User();
            $user->setServiceContainer($this->getServiceContainer());
            $user->unserialize($userData);

            $this->currentUser = $user;
        }
    }

    /**
     * @return \Application\Model\User
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }
}
