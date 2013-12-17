<?php

namespace Application\Admin;

use Application\Admin\Form\LoginForm;
use Application\Model\User;

class Login extends BaseAdminController
{
    public function get()
    {
        return $this->renderPage();
    }

    public function post()
    {
        $form = new LoginForm($this->getServiceContainer());
        $form->loadParams();

        if ($form->isValid()) {
            $username = $form->getUsername();
            $password = $form->getPassword();

            $user = $this->getServiceContainer()
                ->getMapperContainer()
                ->getUserMapper()
                ->fetchByUsernameAndPassword($username, $password);

            if ($user instanceof User && $user->isAdminUser()) {
                $user->insertNewLoginHistory();

                $this->getServiceContainer()->getSession()->set('is_admin_logged_in', 1);
                $this->getServiceContainer()->getSession()->set('admin_user_data', $user->serialize());

                return $this->redirect('/admin');
            } else {
                $logMessage = 'Yönetim paneli için hatalı kullanıcı bilgileri ile deneme yapıldı.';
                $logContext = array(
                    'username' => $form->getUsername(),
                    'password' => $form->getPassword(),
                    'ip' => $this->getServiceContainer()->getRequest()->getClientIp(),
                    'headers' => $this->getServiceContainer()->getRequest()->headers
                );

                $this->getServiceContainer()
                    ->getMonolog()
                    ->warning($logMessage, $logContext);

                $templateParams = array(
                    'message_type' => 'danger',
                    'message' => 'Hatalı kullanıcı adı ya da şifre!',
                );
            }
        } else {
            $templateParams = array(
                'message_type' => 'danger',
                'message' => 'Oturum açılırken bazı sorunlar çıktı',
                'form_violations' => $form->getMessages()
            );
        }

        return $this->renderPage($templateParams);
    }

    protected function renderPage(array $templateParams = array())
    {
        $configs = $this->getServiceContainer()->getConfigs();
        $params = array(
            'recaptcha_public_key' => $configs['recaptcha']['public_key']
        );
        $params = array_merge($params, $templateParams);

        return $this->render('admin/login.twig', $params);
    }
}
