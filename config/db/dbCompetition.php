<?php
namespace config;

use tvustat\DBTableEntry;
use tvustat\Competition;

class dbCompetition extends dbTableDescription
{

    public const DBNAME = "competitions";

    public const ID = "competitionID";

    public const NAMEID = "competitionNameID";

    public const LOCATIONID = "locationNameID";

    public const DATE = "competitionDate";

    public const VALUES = array(
        0 => self::ID,
        1 => self::NAMEID,
        2 => self::LOCATIONID,
        3 => self::DATE
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
    
    /**
     * 
     * @param DBTableEntry $dbTableEntry
     */
    public static function classToCollumns(Competition $competition)
    {
        return array(
          0 => $competition->getId(),
          1 => $competition->getNameID(),
          2 => $competition->getLocationID(),
          3 => $competition->getDate()
        );
    }

}