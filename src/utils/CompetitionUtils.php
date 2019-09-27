<?php
namespace tvustat;

class CompetitionUtils
{

    public static function checkCompetitionReadyForInsertion(Competition $competition)
    {
        return ($competition->getDate() != NULL && //
        $competition->getLocation() != NULL && //
        $competition->getLocation()->getId() != NULL && //
        $competition->getName() != NULL && //
        $competition->getName()->getId() != NULL);
    }

    public static function checkLocationReadyForInsertion(CompetitionLocation $competitionLocation)
    {
        return ($competitionLocation->getFacility() != NULL && //
        $competitionLocation->getVillage() != NULL);
    }

    public static function checkNameReadyForInsertion(CompetitionName $competitionName)
    {
        return ($competitionName->getCompetitionName() != NULL);
    }
}

