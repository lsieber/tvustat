<?php
namespace tvustat;

class WindUtils
{

    public static function wind2DB($wind)
    {
        return ($wind == "") ? NULL : $wind;
    }

    public static function db2Wind($dbWind)
    {
        return ($dbWind == NULL) ? "" : $dbWind;
    }
}

