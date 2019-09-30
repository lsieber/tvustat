<?php

namespace tvustat;
class Conventions
{

    /**
     *
     * @param int $lauf
     * @return boolean
     */
    public static function isLauf($lauf)
    {
        return ($lauf == 1 or $lauf == 5);
    }

    /**
     *
     * @param int $lauf
     * @return boolean
     */
    public static function isSingleEvent($lauf)
    {
        return ($lauf <= 4);
    }

    /**
     *
     * @param int $lauf
     * @return boolean
     */
    public static function timeAsPerformance(Disziplin $disziplin)
    {
        return self::isLauf($disziplin->getLauf());
    }

    /**
     *
     * @param int $lauf
     * @return boolean
     */
    public static function diziplinAsc(Disziplin $disziplin)
    {
        return self::isLauf($disziplin->getLauf());
    }

    /**
     *
     * @param int $lauf
     * @return boolean
     */
    public static function diziplinCombinedEvent(Disziplin $disziplin)
    {
        return ! ($disziplin->getLauf() != 4 and $disziplin->getLauf() != 6);
    }

    /**
     *
     * @param int $lauf
     * @return boolean
     */
    public static function multpleResultsOfSamePersonAccepted(Disziplin $disziplin)
    {
        return $disziplin->getLauf() >= 5;
    }
}

