<?php
namespace tvustat;

use config\dbPerformance;
use function tvustat\DBInputUtils\formatPerformanceToFloat;

class DBInputUtils
{

    /**
     * formats a performance from string to its value which can be entered into the DB
     *
     * @param Performance $performance
     * @return mixed|number|boolean
     */
    public static function formatPerformanceForDB(Performance $performance)
    {
        return self::formatPerformanceToFloat($performance->getDisziplin()->isTime(), $performance->getPerformance());
    }

    public static function formatPerformanceToFloat(bool $isTime, $performanceValue)
    {
        return ($isTime) ? TimeUtils::time2seconds($performanceValue) : $performanceValue;
    }

    /**
     *
     * @param Performance $performance
     * @return boolean
     */
    public static function checkInputRange(Performance $performance)
    {
        $perfModified = self::formatPerformanceForDB($performance);
        $minValueOk = $perfModified >= $performance->getDisziplin()->getMinValue();
        $maxValueOk = $perfModified <= $performance->getDisziplin()->getMaxValue();
        return $minValueOk && $maxValueOk;
    }

    /**
     * Checks if the Team Type of the Performance is the same as the Team type of the disziplin
     *
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

    public static function validPerformanceForInput(Performance $performance)
    {
        return self::checkInputRange($performance) && self::checkTeamTypeMatch($performance);
    }

    public static function performanceExistsInDb(DBMaintainer $db, Performance $performance)
    {
        return ! is_null($db->getbyValues->performance($performance->athlete->getId(), $performance->disziplin->getId(), $performance->competition->getId(), $performance->getPerformance()));
    }

    public static function performanceIsFromTVUBuchAndExists(DBMaintainer $db, Performance $p)
    {
        if (CompetitionUtils::isFromTVUBuch($p->getCompetition())) {
            $existingPerformances = $db->getbyValues->performanceAthleteYear($p->getDisziplin()
                ->getId(), $p->getAthlete()
                ->getId(), DateFormatUtils::formatDateaAsYear($p->getCompetition()
                ->getDate()));
            if (sizeof($existingPerformances) > 0) {
                foreach ($existingPerformances as $value) {
                    if ($p->getDisziplin()
                        ->getSorting()
                        ->sortASC()) {
                        if ($value[dbPerformance::PERFORMANCE] <= $p->getPerformance()) {
                            return true;
                        }
                    } else {
                        if ($value[dbPerformance::PERFORMANCE] >= $p->getPerformance()) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }
}

