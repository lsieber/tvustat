<?php
namespace tvustat;

class StringConversionUtils
{

    public static function stringTrueFalseToBool(string $stringBoolean)
    {
        if ($stringBoolean == "true")
            return TRUE;
        elseif ($stringBoolean == "false")
            return FALSE;
        else
            return null;
    }
}
?>