<?php
namespace config;

class dbCompetitionNames extends dbTableDescription
{

    public const DBNAME = "competitionnames";

    public const ID = "competitionNameID";

    public const NAME = "competitionName";

    public const VALUES = array(
        0 => self::ID,
        1 => self::NAME
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