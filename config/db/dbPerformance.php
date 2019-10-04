<?php
namespace config;

use tvustat\ConnectionPreloaded;
use tvustat\DateFormatUtils;
use tvustat\Performance;

class dbPerformance extends dbTableDescription
{

    public const DBNAME = "performances";

    public const ID = "ID";

    public const ATHLETEID = "athleteID";

    public const COMPETITOINID = "competitionID";

    public const DISZIPLINID = "disziplinID";

    public const PERFORMANCE = "performance";

    public const WIND = "wind";

    public const PLACE = "placement";

    public const LASTCHANGE = "lastChange";

    public static function getIDString()
    {
        return self::ID;
    }

    public const VALUES = array(
        self::ID => 0,
        self::ATHLETEID => 1,
        self::DISZIPLINID => 2,
        self::COMPETITOINID => 3,
        self::PERFORMANCE => 4,
        self::WIND => 5,
        self::PLACE => 6,
        self::LASTCHANGE => 7
    );

    /**
     *
     * {@inheritdoc}
     * @see \config\dbTableDescription::getCollumNames()
     */
    public static function getCollumNames()
    {
        return self::VALUES;
    }

    /**
     *
     * {@inheritdoc}
     * @see \config\dbTableDescription::getTableName()
     */
    public static function getTableName()
    {
        return self::DBNAME;
    }

    /**
     *
     * @param Performance $performance
     */
    public static function classToCollumns($performance)
    {
        return array(
            0 => $performance->getId(),
            1 => $performance->getAthlete()->getId(),
            2 => $performance->getDisziplin()->getId(),
            3 => $performance->getCompetition()->getId(),
            4 => $performance->getPerformance(),
            5 => $performance->getWind(),
            6 => $performance->getPlacement(),
            7 => DateFormatUtils::formatDateForDB(new \DateTime())
        );
    }

    public static function performanceFromAsocArray($r, ConnectionPreloaded $conn)
    {
        return new Performance( //
        dbDisziplin::disziplinFromAsocArray($r, $conn), //
        dbAthletes::athleteFromAsocArray($r, $conn), //
        dbCompetition::competitionFromAsocArray($r, $conn), //
        $r[self::PERFORMANCE], //
        $r[self::WIND], //
        $r[self::PLACE], //
        $r[self::ID]); //
        
    }


}