<?php
namespace tvustat;

class TeamTypeUtils
{

    static function ofClass(string $classname)
    {
        switch ($classname) {
            case Person::class:
                return 1;
            case Team::class:
                return 2;
            default:
                return NULL;
        }
    }
}

