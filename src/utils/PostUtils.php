<?php
namespace tvustat;

class PostUtils
{

    /**
     *
     * @param string $postKey
     * @param string $notExistanceValue
     * @return NULL|mixed
     */
    public static function value(string $postKey, string $notExistanceValue = NULL)
    {
        return array_key_exists($postKey, $_POST) ? $_POST[$postKey] : $notExistanceValue;
    }
}
?>