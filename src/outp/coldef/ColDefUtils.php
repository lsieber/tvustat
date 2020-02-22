<?php
namespace tvustat;

class ColDefUtils
{
    
    public static function athleteLink(Athlete $athlete) {
        return "<a onclick='openAthlete(".$athlete->getID().")'href='#'>" . $athlete->getFullName(). "</a>";
    }
}

