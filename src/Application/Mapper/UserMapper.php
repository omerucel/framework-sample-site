<?php

namespace Application\Mapper;

use Application\Model\FacebookAccount;
use Application\Model\User;

class UserMapper extends BaseMapper
{
    /**
     * @param $data
     * @return User|null
     */
    public function loadUserObject($data)
    {
        return parent::loadObject($data, 'Application\Model\User');
    }

    /**
     * @param $data
     * @return FacebookAccount|null
     */
    public function loadFacebookAccountObject($data)
    {
        return parent::loadObject($data, 'Application\Model\FacebookAccount');
    }

    /**
     * @param User $user
     */
    public function insert(User $user)
    {
        $params = array(
            'username' => $user->username,
            'password' => sha1($user->password)
        );

        $this->getConnection()->insert('user', $params);
        $user->id = $this->getConnection()->lastInsertId();
    }

    /**
     * @param $username
     * @param $password
     * @return User
     */
    public function fetchByUsernameAndPassword($username, $password)
    {
        $query = $this->createQueryBuilder()
            ->select('*')
            ->from('user', 'u')
            ->where('u.username = :username')
            ->andWhere('u.password = :password')
            ->andWhere('u.status = 1');

        $params = array(
            'username' => $username,
            'password' => sha1($password)
        );

        $data = $this->getConnection()->executeQuery($query, $params)->fetch();
        return $this->loadUserObject($data);
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function fetchFacebookAccount($userId)
    {
        $query = $this->createQueryBuilder()
            ->select('*')
            ->from('facebook_account', 'fa')
            ->where('fa.user_id = :user_id');

        $params = array(
            'user_id' => $userId
        );

        $data = $this->getConnection()->executeQuery($query, $params);
        return $this->loadFacebookAccountObject($data);
    }

    /**
     * @param $userId
     * @return bool
     */
    public function isAdminUser($userId)
    {
        $query = $this->createQueryBuilder()
            ->select('COUNT(*) AS count')
            ->from('admin_user', 'au')
            ->where('au.user_id = :user_id')
            ->andWhere('au.status = 1');

        $params = array(
            ':user_id' => $userId
        );

        return $this->getConnection()->executeQuery($query, $params)->fetchColumn();
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

        $this->getConnection()->insert('user_login_history', $params);
    }
}
