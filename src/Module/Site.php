<?php

namespace Module;

class Site extends \OU\ModuleAbstract
{
    /**
     * @param \OU\Route $route
     */
    public function dispatch(\OU\Route $route)
    {
        var_dump($route);
    }
}
