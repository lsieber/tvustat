<?php
namespace config;

use tvustat\Athlete;
use tvustat\ConnectionPreloaded;

class dbAthletes extends dbTableDescription
{

    public const DBNAME = "athletes";

    public const ID = "athleteID";

    public const FULLNAME = "fullName";

    public const GENDERID = "genderID";

    public const TEAMTYPEID = "teamTypeID";

    public const DATE = "date";

    public const lICENCE = "licenceNumber";

    public static function getIDString()
    {
        return self::ID;
    }

    public const VALUES = array(
        self::ID => 0,
        self::FULLNAME => 1,
        self::GENDERID => 2,
        self::TEAMTYPEID => 3,
        self::DATE => 4,
        self::lICENCE => 5
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
     * @param Athlete $athlete
     * @return array
     */
    public static function classToCollumns($athlete)
    {
        return array(
            0 => $athlete->getId(),
            1 => $athlete->getFullName(),
            2 => $athlete->getGender()->getId(),
            3 => $athlete->getTeamType()->getId(),
            4 => $athlete->getDateForDB(),
            5 => NULL
        );
    }

    public static function athleteFromAsocArray($r, ConnectionPreloaded $conn)
    {
        return new Athlete( //
        $r[self::FULLNAME], //
        new \DateTime($r[self::DATE]), //
        $conn->getGender($r[self::GENDERID]), //
        $conn->getTeamType($r[self::TEAMTYPEID]), //
        $r[self::ID]);
    }
}