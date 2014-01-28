<?php

namespace Application\Utility;

class FileOperation
{
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
    public static function downloadUrl($url, $path)
    {
        $content = file_get_contents($url);
        file_put_contents($path, $content);
        chmod($path, 0777);
    }
}
