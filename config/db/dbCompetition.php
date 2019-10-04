<?php
namespace config;

use tvustat\Competition;
use tvustat\CompetitionLocation;
use tvustat\CompetitionName;
use tvustat\ConnectionPreloaded;
use tvustat\DateFormatUtils;

class dbCompetition extends dbTableDescription
{

    public const DBNAME = "competitions";

    public const ID = "competitionID";

    public const NAMEID = "competitionNameID";

    public const LOCATIONID = "locationNameID";

    public const DATE = "competitionDate";

    public static function getIDString()
    {
        return self::ID;
    }

    // public const VALUES = array(
    // self::ID => 0,
    // 1 => self::NAMEID,
    // 2 => self::LOCATIONID,
    // 3 => self::DATE
    // );
    public const VALUES = array(
        self::ID => 0,
        self::NAMEID => 1,
        self::LOCATIONID => 2,
        self::DATE => 3
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
     * @param Competition $competition
     */
    public static function classToCollumns($competition)
    {
        return array(
            0 => $competition->getId(),
            1 => $competition->getName()->getId(),
            2 => $competition->getLocation()->getId(),
            3 => $competition->getFormatedDateForDB()
        );
    }

    public static function competitionFromAsocArray($r, ConnectionPreloaded $conn)
    {
        return new Competition( //
        new CompetitionName($r[dbCompetitionNames::NAME], $r[self::NAMEID]), //
        new CompetitionLocation($r[dbCompetitionLocations::VILLAGE], $r[dbCompetitionLocations::FACILITY], $r[self::LOCATIONID]), //
        DateFormatUtils::DateTimeFromDB($r[self::DATE]), //
        $r[self::ID]); //
    }
}