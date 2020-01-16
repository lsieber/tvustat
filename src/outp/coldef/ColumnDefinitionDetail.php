<?php
namespace tvustat;

class ColumnDefinitionDetail implements ColumnDefinition
{

    public function bestListHeaders()
    {
        return array( //
            "Resultat",
            "Name",
            "Jg",
            "Ort",
            "Datum",
            "Detail"
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
            DateFormatUtils::formatDateForBL($performance->getCompetition()->getDate()),
            $performance->getDetail()
        );
    }

    public function numberBestListElements()
    {
        return sizeof(self::bestListHeaders());
    }
}

