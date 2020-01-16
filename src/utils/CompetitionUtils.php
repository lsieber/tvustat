<?php
namespace tvustat;

class CompetitionUtils
{


    
    /**
     * 
     * @param CompetitionName $competitionName
     * @return string
     */
    public static function formatCompetitionName(CompetitionName $competitionName)
    {
        return ($competitionName->getCompetitionName() == "k.A.")? "" : $competitionName->getCompetitionName();
    }
    
    /**
     * 
     * @param CompetitionLocation $competitionLocation
     * @return string
     */
    public static function formatCompetitionVillage(CompetitionLocation $competitionLocation)
    {
        return ($competitionLocation->getVillage() == "k.A.")? "" : $competitionLocation->getVillage();
    }
        
    /**
     * 
     * @param Competition $competition
     * @return boolean
     */
    public static function isFromTVUBuch(Competition $competition)
    {
        return DateFormatUtils::onlyYearValid($competition->getDate());
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

