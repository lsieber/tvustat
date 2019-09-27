<?php
namespace config;

use tvustat\Athlete;

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

    public const VALUES = array(
        0 => self::ID,
        1 => self::FIRSTNAME,
        2 => self::LASTNAME,
        3 => self::GENDERID,
        4 => self::TEAMTYPEID,
        5 => self::DATE,
        6 => self::lICENCE
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
}