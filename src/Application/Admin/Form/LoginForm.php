<?php

namespace Application\Admin\Form;

use Application\BaseForm;
use Symfony\Component\Validator\Constraints as Assert;

class LoginForm extends BaseForm
{
    /**
     * @Assert\NotBlank(message = "Kullanıcı adı gerekli!")
     */
    protected $username;

    /**
     * @Assert\NotBlank(message = "Şifre gerekli!")
     */
    protected $password;

    /**
     * @Assert\True(message = "Geçersiz güvenlik kodu")
     */
    public function isValidSecurityCode()
    {
        return $this->getServiceContainer()->getCaptcha()->check()->isValid();
    }

    /**
     * @return mixed
     */
    public function loadParams()
    {
        $request = $this->getServiceContainer()->getRequest();
        $this->username = $request->get('username');
        $this->password = $request->get('password');
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $this->validate();
        return $this->hasError() == false;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
}