<?php
namespace tvustat;

class CompetitionUtils
{

    public static function isFromTVUBuch(Competition $competition)
    {
        echo $competition->getDate()->format("n.j");
        return $competition->getDate()->format("n.j") == "1.1";
    }

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
        return (/*$competitionLocation->getFacility() != NULL && //*/
        $competitionLocation->getVillage() != NULL // for now we only consider the Village
        );
    }

    public static function checkNameReadyForInsertion(CompetitionName $competitionName)
    {
        return ($competitionName->getCompetitionName() != NULL);
    }
}

