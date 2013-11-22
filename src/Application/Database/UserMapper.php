<?php

namespace Application\Database;

use Application\Model\User;

class UserMapper extends BaseMapper
{
    /**
     * @param User $user
     */
    public function insert(User $user)
    {
        $params = array(
            'username' => $user->username,
            'password' => sha1($user->password)
        );

        $user->id = $this->getServiceContainer()
            ->getConnection()
            ->insertWrapper('user', $params);
    }

    /**
     * @param $username
     * @param $password
     * @return User
     */
    public function fetchByUsernameAndPassword($username, $password)
    {
        $sql = 'SELECT * FROM user WHERE username = :username AND password = :password AND status = 1';
        $params = array(
            'username' => $username,
            'password' => sha1($password)
        );

        $user = $this->getServiceContainer()
            ->getConnection()
            ->fetchOneObject($sql, $params, 'Application\Model\User');

        if ($user != null) {
            /**
             * @var User $user
             */
            $user->setServiceContainer($this->getServiceContainer());
        }

        return $user;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function fetchFacebookAccount($userId)
    {
        $sql = 'SELECT * FROM facebook_account WHERE user_id = :user_id';
        $params = array(
            'user_id' => $userId
        );

        $facebookAccount = $this->getServiceContainer()
            ->getConnection()
            ->fetchOneObject($sql, $params, 'Application\Model\FacebookAccount');

        return $facebookAccount;
    }

    /**
     * @param $userId
     * @return bool
     */
    public function isAdminUser($userId)
    {
        $sql = 'SELECT COUNT(*) AS count FROM admin_user WHERE user_id = :user_id AND status = 1';
        $params = array(
            'user_id' => $userId
        );

        $status = $this->getServiceContainer()
            ->getConnection()
            ->fetchColumn($sql, $params);

        return $status == 1;
    }

    /**
     * @param $userId
     */
    public function newLoginHistory($userId)
    {
        $request = $this->getServiceContainer()->getRequest();

        $params = array(
            'user_id' => $userId,
            'logged_in_at' => date('Y-m-d H:i:s'),
            'logged_ip' => $request->getClientIp(),
            'user_agent' => $request->headers->get('User-Agent')
        );

        $this->getServiceContainer()->getConnection()->insertWrapper('user_login_history', $params);
    }
}
