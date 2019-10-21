<?php
namespace config;

use tvustat\ConnectionPreloaded;
use tvustat\PerformanceSource;

class dbPerformanceSource
{

    public const DBNAME = "preformancesource";

    public const ID = "sourceID";

    public const NAME = "sourceName";

    public const LINK = "sourceLink";

    public static function sourceFromAssocArray($r)
    {
        return new PerformanceSource( //
        $r[self::NAME], //
        $r[self::ID], //
        $r[self::LINK]); //
    }
}

