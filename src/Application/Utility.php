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

    /**
     * @param $dir
     */
    public static function rmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") {
                        static::rmdir($dir."/".$object);
                    } else {
                        unlink($dir."/".$object);
                    }
                }
            }

            reset($objects);
            rmdir($dir);
        }
    }

    /**
     * @param $url
     * @param $path
     */
    public static function fileSave($url, $path)
    {
        $content = file_get_contents($url);
        file_put_contents($path, $content);
        chmod($path, 0777);
    }

    public function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }

        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }
}
