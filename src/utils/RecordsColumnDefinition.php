<?php

namespace tvustat;
trait RecordsColumnDefinition
{

    protected static function recordHeaders()
    {
        return array( //
            "Leistung",
            "Name",
            "Jg",
            "Leistung",
            "Jahr"
        );
    }

    protected static function recordElements(Performance $performance)
    {
        return array( //
            $performance->getDisziplin()->getName(),
            $performance->getPerson()->getFullName(),
            $performance->getPerson()->getBorn(),
            $performance->getFormatedPerformance(),
            $performance->getCompetition()->getFormatedDate()
        );
    }

    protected static function numberRecordElements()
    {
        // $person = new Person("", "", 0, 0, 0);
        // $comp = new Competition("", "", new DateTime('now'));
        // $disziplin = new Disziplin("", 1, 0, 1);
        // return sizeof(HtmlGenerator::bestListElements(new Performance(0, $disziplin, $person, $comp)));
        return sizeof(self::recordHeaders());
    }
}

