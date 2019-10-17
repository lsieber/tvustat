<?php

namespace tvustat;
class RecordsColumnDefinition
{

    public static function recordHeaders()
    {
        return array( //
            "Leistung",
            "Name",
            "Jg",
            "Leistung",
            "Jahr"
        );
    }

    public static function recordElements(Performance $performance)
    {
        return array( //
            $performance->getDisziplin()->getName(),
            $performance->getPerson()->getFullName(),
            $performance->getPerson()->getBorn(),
            $performance->getFormatedPerformance(),
            $performance->getCompetition()->getFormatedDate()
        );
    }

    public static function numberRecordElements()
    {
        return sizeof(self::recordHeaders());
    }
}

