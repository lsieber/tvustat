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

    public const DISZIPLINTYPE = "disziplinTypeID";

    public const TEAMTYPEID = "teamTypeID";

    public const ORDER = "orderNumber";

    public const MINVAL = "minVal";

    public const MAXVAL = "maxVal";

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
        self::MINVAL => 8,
        self::MAXVAL => 9,
        self::SWISSATHLETICS => 10
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
            6 => $disziplin->getTeamType()->getId(),
            7 => $disziplin->getOrderNumber(),
            8 => $disziplin->getMinValue(),
            9 => $disziplin->getMaxValue(),
            10 => NULL
        );
    }

    public static function disziplinFromAsocArray($r, ConnectionPreloaded $conn)
    {
        return new Disziplin( //
        $r[self::NAME], //
        $conn->getSorting($r[self::SORTINGID]), //
        $r[self::ORDER], //
        $r[self::ISTIME], //
        $r[self::DECIMAL], //
        $conn->getDisziplinType($r[self::DISZIPLINTYPE]), //
        $conn->getTeamType($r[self::TEAMTYPEID]), //
        $r[self::MINVAL], // miN vALUE
        $r[self::MAXVAL], // Max Value 
        $r[self::ID]);
    }
}