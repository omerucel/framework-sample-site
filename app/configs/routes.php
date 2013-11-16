<?php

return array(
    '/' => 'Application\Site\HomePage',
    '/500' => 'Application\Site\InternalError',
    '/facebook-login' => 'Application\Site\FacebookLogin',
    '/facebook-login-callback' => 'Application\Site\FacebookLoginCallback',
    '/logout' => 'Application\Site\Logout',

    '/admin' => 'Application\Admin\Dashboard',
    '/admin/500' => 'Application\Admin\InternalError',
    '/admin/login' => 'Application\Admin\Login',
    '/admin/logout' => 'Application\Admin\Logout'
);
