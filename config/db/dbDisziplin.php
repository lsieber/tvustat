<?php
namespace config;

use tvustat\ConnectionPreloaded;
use tvustat\Disziplin;

class dbDisziplin extends dbTableDescription
{

    public const DBNAME = "disziplins";

    public const ID = "disziplinID";

    public const NAME = "disziplinName";

    public const SORTINGID = "sortingID";

    public const ISTIME = "isTime";

    public const DECIMAL = "decimalPlaces";

    public const DISZIPLINTYPE = "disziplinType";

    public const TEAMTYPEID = "teamTypeID";

    public const ORDER = "orderNumber";

    public const SWISSATHLETICS = "swissAthleticsID";

    public static function getIDString()
    {
        return self::ID;
    }

    // public const VALUES = array(
    // 0 => self::ID,
    // 1 => self::NAME,
    // 2 => self::SORTINGID,
    // 3 => self::ISTIME,
    // 4 => self::DECIMAL,
    // 5 => self::DISZIPLINTYPE,
    // 6 => self::TEAMTYPEID,
    // 7 => self::ORDER,
    // 8 => self::SWISSATHLETICS
    // );
    public const VALUES = array(
        self::ID => 0,
        self::NAME => 1,
        self::SORTINGID => 2,
        self::ISTIME => 3,
        self::DECIMAL => 4,
        self::DISZIPLINTYPE => 5,
        self::TEAMTYPEID => 6,
        self::ORDER => 7,
        self::SWISSATHLETICS => 8
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
     * @param Disziplin $disziplin
     * @return array
     */
    public static function classToCollumns($disziplin)
    {
        return array(
            0 => $disziplin->getId(),
            1 => $disziplin->getName(),
            2 => $disziplin->getSorting()->getId(),
            3 => $disziplin->isTime(),
            4 => $disziplin->isDecimal(),
            5 => $disziplin->getDisziplinType()->getId(),
            6 => $disziplin->getTeamType(),
            7 => $disziplin->getOrderNumber(),
            8 => NULL
        );
    }

    public static function disziplinFromAsocArray($r, ConnectionPreloaded $conn)
    {
        return new Disziplin( //
        $r[self::NAME], //
        $r[self::SORTINGID], //
        $r[self::ORDER], //
        $r[self::ISTIME], //
        $r[self::DECIMAL], //
        $conn->getDisziplinType(self::DISZIPLINTYPE), //
        $conn->getTeamType(self::TEAMTYPEID), //
        NULL, // miN vALUE
        NULL, // Max Value TODO
        $r[self::ID]);
    }
}