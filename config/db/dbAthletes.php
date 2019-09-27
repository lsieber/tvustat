<?php
namespace config;

use tvustat\Athlete;
use tvustat\ConnectionPreloaded;
use tvustat\Person;

class dbAthletes extends dbTableDescription
{

    public const DBNAME = "athletes";

    public const ID = "athleteID";

    public const FIRSTNAME = "firstName";

    public const LASTNAME = "lastName";

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
        self::FIRSTNAME => 1,
        self::LASTNAME => 2,
        self::GENDERID => 3,
        self::TEAMTYPEID => 4,
        self::DATE => 5,
        self::lICENCE => 6
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
            1 => $athlete->getFirstName(),
            2 => $athlete->getLastName(),
            3 => $athlete->getGender()->getId(),
            4 => $athlete->getTeamType()->getId(),
            5 => $athlete->getDateForDB(),
            6 => NULL
        );
    }

    public static function personFromAsocArray($r, ConnectionPreloaded $conn)
    {
        return new Person( //
        $r[self::FIRSTNAME], //
        $r[self::LASTNAME], //
        new \DateTime($r[self::DATE]), //
        $conn->getGender($r[self::GENDERID]), //
        $conn, //
        $r[self::ID]);
    }
}