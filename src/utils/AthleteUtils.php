<?php
namespace tvustat;

class AthleteUtils
{

    public static function checkAthleteReadyForInsertion(Athlete $athlete)
    {
        return ($athlete->getFullName() != NULL && //
        $athlete->getGender() != NULL && //
        $athlete->getTeamType() != NULL);
    }
}

