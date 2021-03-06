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

    public const SOURCE = "sourceID";

    public const WIND = "wind";

    public const PLACE = "placement";
    
    public const MANUALTIME = "manualTiming";

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
        self::MANUALTIME => 7,
        self::SOURCE => 8,
        self::LASTCHANGE => 9
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
        $sourceId = is_null($performance->getSource()) ? NULL : $performance->getSource()->getId();
        return array(
            0 => $performance->getId(),
            1 => $performance->getAthlete()->getId(),
            2 => $performance->getDisziplin()->getId(),
            3 => $performance->getCompetition()->getId(),
            4 => $performance->getPerformance(),
            5 => $performance->getWind(),
            6 => $performance->getPlacement(),
            7 => $performance->getManualTiming(),
            8 => $sourceId,
            9 => DateFormatUtils::nowForDB()
        );
    }

    public static function array2Elmt($r, ConnectionPreloaded $conn)
    {
        return new Performance( //
        dbDisziplin::array2Elmt($r, $conn), //
        dbAthletes::array2Elmt($r, $conn), //
        dbCompetition::array2Elmt($r, $conn), //
        $r[self::PERFORMANCE], //
        $r[self::WIND], //
        $r[self::PLACE], //
        self::manualTiming($r[self::MANUALTIME]), //    
        self::getSource($r[self::SOURCE], $conn), //
        $r[self::ID], //
        self::getDetail($r)); //
    }

    private static function manualTiming($dbValue) {
        return ($dbValue == NULL || $dbValue == 0)? FALSE : TRUE;
    }
    
    
    private static function getDetail($r)
    {
        if (! array_key_exists(dbPerformanceDetail::DETAIL, $r)) {
            return NULL;
        }
        return $r[dbPerformanceDetail::DETAIL];
    }

    private static function getSource($sourceId, ConnectionPreloaded $conn)
    {
        return (is_null($sourceId)) ? null : $conn->getSource($sourceId);
    }
}