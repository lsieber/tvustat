<?php
namespace config;

use tvustat\PerformanceSource;

class dbPerformanceSource
{

    public const DBNAME = "performancesource";

    public const ID = "sourceID";

    public const NAME = "sourceName";

    public const LINK = "sourceLink";
    
    public const SOURCETYPEID = "sourceTypeID";

    public static function sourceFromAssocArray($r)
    {
        return new PerformanceSource( //
        $r[self::NAME], //
        $r[self::ID], //
        $r[self::SOURCETYPEID], //
        $r[self::LINK]); //
    }
}

