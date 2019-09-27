<?php
namespace config;

class dbCompetitionNames extends dbTableDescription
{

    public const DBNAME = "competitionlocations";

    public const ID = "competitionLocationID";

    public const VILLAGE = "village";
    
    public const FACILITY = "facility";
    
    public const VALUES = array(
        0 => self::ID,
        1 => self::VILLAGE,
        2 => self::FACILITY
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