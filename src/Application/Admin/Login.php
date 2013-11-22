<?php

namespace Application\Admin;

use Application\Model\User;

class Login extends BaseAdminController
{
    public function get()
    {
        return $this->renderPage();
    }

    public function post()
    {
        if (!$this->getServiceContainer()->getCaptcha()->check()->isValid()) {
            $templateParams = array(
                'error' => 'Güvenlik kodu hatalı.'
            );

            return $this->renderPage($templateParams);
        }

        $request = $this->getServiceContainer()->getRequest();
        $username = $request->get('username');
        $password = $request->get('password');

        $user = $this->getServiceContainer()
            ->getMapperContainer()
            ->getUserMapper()
            ->fetchByUsernameAndPassword($username, $password);

        if ($user instanceof User && $user->isAdminUser()) {
            $user->insertNewLoginHistory();

            $this->getServiceContainer()->getSession()->set('is_admin_logged_in', 1);
            $this->getServiceContainer()->getSession()->set('admin_user_data', $user->serialize());

            return $this->redirect('/admin');
        }

        $logMessage = 'Yönetim paneli için hatalı kullanıcı bilgileri ile deneme yapıldı.';
        $logContext = array(
            'username' => $username,
            'password' => $password,
            'ip' => $request->getClientIp(),
            'headers' => $request->headers
        );

        $this->getServiceContainer()
            ->getMonolog()
            ->warning($logMessage, $logContext);

        $templateParams = array(
            'error' => 'Kullanıcı adı veya şifre hatalı.'
        );

        return $this->renderPage($templateParams);
    }

    protected function renderPage(array $templateParams = array())
    {
        $params = array(
            'captcha_html' => $this->getServiceContainer()->getCaptcha()->html()
        );
        $params = array_merge($params, $templateParams);

        return $this->render('admin/login.twig', $params);
    }
}
