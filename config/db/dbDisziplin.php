<?php
namespace config;

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

    public const VALUES = array(
        0 => self::ID,
        1 => self::NAME,
        2 => self::SORTINGID,
        3 => self::ISTIME,
        4 => self::DECIMAL,
        5 => self::DISZIPLINTYPE,
        6 => self::TEAMTYPEID,
        7 => self::ORDER,
        8 => self::SWISSATHLETICS
    );

    /**
     *
     * {@inheritdoc}
     * @see \config\dbTableDescription::getCollumNames()
     */
    public function getCollumNames()
    {
        return self::VALUES;
    }

    /**
     *
     * {@inheritdoc}
     * @see \config\dbTableDescription::getTableName()
     */
    public function getTableName()
    {
        return self::DBNAME;
    }
    public static function classToCollumns($value)
    {}

}