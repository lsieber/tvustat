<?php
namespace tvustat;

class ColumnDefinitionBasic implements ColumnDefinition
{

    public function bestListHeaders()
    {
        return array( //
            "Resultat",
            "Name",
            "Jg",
            "Ort",
            "Datum"
        );
    }

    public function bestListElements(Performance $performance)
    {
        return array( //
            $performance->getFormatedPerformance(),
            $performance->getAthlete()->getFullName(),
            DateFormatUtils::formatBirthYearForBL($performance->getAthlete()->getDate()),
            CompetitionUtils::formatCompetitionVillage($performance->getCompetition()
                ->getLocation()),
            DateFormatUtils::formatDateForBL($performance->getCompetition()->getDate())
        );
    }

    public function numberBestListElements()
    {
        return sizeof(self::bestListHeaders());
    }
}

