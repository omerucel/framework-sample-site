<?php

namespace Application\Mapper;

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
        $data = $this->createQueryBuilder()
            ->select('*')
            ->from('user', 'u')
            ->where('u.username = :username')
            ->andWhere('u.password = :password')
            ->andWhere('u.status = 1')
            ->setParameter(':username', $username)
            ->setParameter(':password', sha1($password))
            ->execute()
            ->fetch();

        return $this->loadUserObject($data);
    }

    /**
     * @param $userId
     * @return bool
     */
    public function isAdminUser($userId)
    {
        return $this->createQueryBuilder()
            ->select('COUNT(*) AS count')
            ->from('admin_user', 'au')
            ->where('au.user_id = :user_id')
            ->andWhere('au.status = 1')
            ->setParameter(':user_id', $userId)
            ->execute()
            ->fetchColumn() == 1;
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
