<?php
namespace config;

use tvustat\CompetitionLocation;
use tvustat\ConnectionPreloaded;

class dbCompetitionLocations extends dbTableDescription
{

    public const DBNAME = "competitionlocations";

    public const ID = "competitionLocationID";

    public const VILLAGE = "village";

    public const FACILITY = "facility";

    public const VALUES = array(
        self::ID => 0,
        self::VILLAGE => 1,
        self::FACILITY => 2
    );

    public static function getIDString()
    {
        return self::ID;
    }

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
     * @param CompetitionLocation $competitionLocation
     * @return array
     */
    public static function classToCollumns($competitionLocation)
    {
        return array(
            0 => $competitionLocation->getId(),
            1 => $competitionLocation->getVillage(),
            2 => $competitionLocation->getFacility()
        );
    }
    
    public static function competitionLocationFromAsocArray($r, ConnectionPreloaded $conn)
    {
        return new CompetitionLocation( //
            $r[self::VILLAGE], //
            $r[self::FACILITY], //
            $r[self::ID]);
    }
}