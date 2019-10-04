<?php

namespace tvustat;
trait BestListColumnDefinition
{

    protected static function bestListHeaders()
    {
        return array( //
            "Disziplin",
            "Name",
            "Jg",
            "Ort",
            "Datum"
        );
    }

    protected static function bestListElements(Performance $performance)
    {
        return array( //
            $performance->getFormatedPerformance(),
            $performance->getAthlete()->getFullName(),
            DateFormatUtils::formatDateForBL($performance->getAthlete()->getDate()),
            $performance->getCompetition()->getLocation()->getVillage(),
            DateFormatUtils::formatDateForBL($performance->getCompetition()->getDate())
        );
    }

    protected static function numberBestListElements()
    {
        // $person = new Person("", "", 0, 0, 0);
        // $comp = new Competition("", "", new DateTime('now'));
        // return sizeof(HtmlGenerator::bestListElements(new Performance(0, NULL, $person, $comp)));
        return sizeof(self::bestListHeaders());
    }
}

