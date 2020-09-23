<?php
namespace tvustat;

abstract class DBInputUtils
{

    /**
     * formats a performance from string to its value which can be entered into the DB
     *
     * @param Performance $performance
     * @return mixed|number|boolean
     */
    public static function formatPerformanceForDB(Performance $performance)
    {
        return ($performance->getDisziplin()->isTime()) ? TimeUtils::time2seconds($performance->getPerformance()) : $performance->getPerformance();
    }

    /**
     *
     * @param Performance $performance
     * @return boolean
     */
    public static function checkInputRange(Performance $performance)
    {
        $perfModified = DBInputUtils::formatPerformanceForDB($performance);
        $minValueOk = $perfModified >= $performance->getDisziplin()->getMinValue();
        $maxValueOk = $perfModified <= $performance->getDisziplin()->getMaxValue();
        return $minValueOk && $maxValueOk;
    }

    /**
     * Checks if the Team Type of the Performance is the same as the Team type of the disziplin
     * @param Performance $performance
     * @return boolean
     */
    public static function checkTeamTypeMatch(Performance $performance)
    {
        return $performance->getAthlete()
            ->getTeamType()
            ->getId() == $performance->getDisziplin()
            ->getTeamType()
            ->getId();
    }
    
    public static function validPerformanceForInput(Performance $performance) {
        return self::checkInputRange($performance) && self::checkTeamTypeMatch($performance);
    }
    
}

