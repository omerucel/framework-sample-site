<?php

namespace Application;

class Utility
{
    /**
     * @param $object
     * @return array
     */
    public static function getPublicVars($object)
    {
        return get_object_vars($object);
    }
}
