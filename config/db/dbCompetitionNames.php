<?php
namespace config;

use tvustat\CompetitionName;
use tvustat\ConnectionPreloaded;

class dbCompetitionNames extends dbTableDescription
{

    public const DBNAME = "competitionnames";

    public const ID = "competitionNameID";

    public const NAME = "competitionName";

    public const VALUES = array(
        self::ID => 0,
        self::NAME => 1
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
     * @param CompetitionName $competitionName
     * @return array
     */
    public static function classToCollumns($competitionName)
    {
        return array(
            0 => $competitionName->getId(),
            1 => $competitionName->getCompetitionName()
        );
    }

    public static function competitionNameFromAsocArray($r, ConnectionPreloaded $conn)
    {
        return new CompetitionName( //
            $r[self::NAME], //
            $r[self::ID]);
    }
}