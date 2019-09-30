<?php
namespace tvustat;

use config\DefaultSettings;

class ConnectionExtension extends ConnectionPreloaded
{

    const PERFORMANCEIDALIAS = "PerfId";

    protected static function getPerformancesOfBLArray($performancesDB)
    {
        $performances = array();
        foreach ($performancesDB as $performanceDB) {
            array_push($performances, self::getPerformanceOfBLArray($performanceDB));
        }
        return $performances;
    }

    protected static function getPerformanceOfBLArray($performanceDB, string $perfIdString = self::PERFORMANCEIDALIAS)
    {
        $disziplin = self::getDisziplin($performanceDB, "DisziplinID");
        $person = self::getPerson($performanceDB, "Mitglied");
        $competition = self::getCompetition($performanceDB, "Wettkampf");
        return new Performance($performanceDB["Leistung"], $disziplin, $person, $competition, $performanceDB[$perfIdString], DefaultSettings::FORMATTIME, DefaultSettings::FORMATDECIMALTWODIGIT);
    }

    protected static function getDisziplinsFromTable($disziplinsDBresult)
    {
        $disziplins = array();
        if (sizeof($disziplinsDBresult) > 0) {
            foreach ($disziplinsDBresult as $disziplinDB) {
                array_push($disziplins, self::getDisziplinFromTable($disziplinDB));
            }
        }
        return $disziplins;
    }

    protected static function getDisziplinFromTable($disziplinDB)
    {
        return self::getDisziplin($disziplinDB, "ID");
    }

    private static function getDisziplin($disziplinDB, string $idString)
    {
        $disziplin = new Disziplin($disziplinDB["Disziplin"], $disziplinDB["Lauf"], $disziplinDB["MinVal"], $disziplinDB["MaxVal"], "", $disziplinDB["Laufsort"], $disziplinDB[$idString]);
        $disziplin->setPointsSLV2010IDMan($disziplinDB["PunkteSLV2010IDMan"]);
        $disziplin->setPointsSLV2010IDWoman($disziplinDB["PunkteSLV2010IDFrau"]);
        $disziplin->setAssociatedCombinedEventIds($disziplinDB["MehrkampfDisziplinenIDs"]);
        return $disziplin;
    }

    protected static function getPersonsFromTable($array_result)
    {
        $persons = array();
        if (sizeof($array_result) > 0) {
            foreach ($array_result as $mitglied) {
                array_push($persons, self::getPersonFromTable($mitglied));
            }
        }
        return $persons;
    }

    protected static function getPersonFromTable($personDB)
    {
        return self::getPerson($personDB, "ID");
    }

    private static function getPerson($personDB, string $idString)
    {
        return new Person($personDB["Vorname"], $personDB["Name"], $personDB["Jg"], $personDB["Geschlecht"], $personDB["aktiv"], $personDB[$idString]);
    }

    protected static function getCompetitionsFromTable($competitionsDB)
    {
        $competitions = array();
        foreach ($competitionsDB as $competitionDB) {
            array_push($competitions, self::getCompetitionFromTable($competitionDB));
        }
        return $competitions;
    }

    protected static function getCompetitionFromTable($competitionDB)
    {
        return self::getCompetition($competitionDB, "ID");
    }

    private static function getCompetition($competitionDB, string $idString)
    {
        return new Competition($competitionDB["WKname"], $competitionDB["Ort"], new \DateTime($competitionDB["Datum"]), $competitionDB[$idString], DefaultSettings::DATEFORMAT);
    }
}

