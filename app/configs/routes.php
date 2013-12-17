<?php

return array(
    '/' => 'Application\Site\HomePage',
    '/500' => 'Application\Site\InternalError',

    '/admin' => 'Application\Admin\Dashboard',
    '/admin/500' => 'Application\Admin\InternalError',
    '/admin/login' => 'Application\Admin\Login',
    '/admin/logout' => 'Application\Admin\Logout',

    '/admin/advertisement' => 'Application\Admin\Advertisement',
    '/admin/meta-information' => 'Application\Admin\MetaInformation'
);
