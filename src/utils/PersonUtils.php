<?php
namespace tvustat;

class PersonUtils
{

    public static function checkAthleteReadyForInsertion(Athlete $athlete)
    {
        return ($athlete->getFirstName() != NULL && //
        $athlete->getGender() != NULL && //
        $athlete->getTeamType() != NULL);
    }
}

