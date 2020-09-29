<?php

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
        return array_key_exists($_POST, $postKey) ? $_POST[$postKey] : $notExistanceValue;
    }
}
?>