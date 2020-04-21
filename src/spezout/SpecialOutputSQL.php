<?php
namespace tvustat;

use config\dbAthletes;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbDisziplin;
use config\dbPerformance;
use config\dbPerformanceDetail;

class SpecialOutputSQL
{

    public static function createCompetition(int $competitionId)
    {
        $sql = self::selectAndJoins();
        $sql .= " WHERE";
        $sql .= self::competition($competitionId);
        return $sql;
    }

    public static function createAthlete(array $athleteIds)
    {
        $sql = self::selectAndJoins();
        $sql .= " WHERE";
        $sql .= self::athlete($athleteIds);
        return $sql;
    }

    /**
     * Returns only a sql if the category control is not ALL, Men, or Women.
     * In these cases the Athlete SQL does include the teams
     *
     * @param array $categories
     * @param array $categoryControl
     * @return string
     */
    private static function selectAndJoins()
    {
        $sql = "SELECT * FROM " . dbPerformance::DBNAME;

        $sql .= " INNER JOIN " . dbCompetition::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::COMPETITOINID . " = " . dbCompetition::DBNAME . "." . dbCompetition::ID;
        $sql .= " INNER JOIN " . dbDisziplin::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::DISZIPLINID . " = " . dbDisziplin::DBNAME . "." . dbDisziplin::ID;
        $sql .= " INNER JOIN " . dbAthletes::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::ATHLETEID . " = " . dbAthletes::DBNAME . "." . dbAthletes::ID;
        $sql .= " LEFT JOIN " . dbPerformanceDetail::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::ID . " = " . dbPerformanceDetail::DBNAME . "." . dbPerformanceDetail::PERFORMANCEID;

        $sql .= " INNER JOIN " . dbCompetitionLocations::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::LOCATIONID . " = " . dbCompetitionLocations::DBNAME . "." . dbCompetitionLocations::ID;
        $sql .= " INNER JOIN " . dbCompetitionNames::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::NAMEID . " = " . dbCompetitionNames::DBNAME . "." . dbCompetitionNames::ID;
        return $sql;
    }

    private static function competition($competitionId)
    {
        return " " . dbPerformance::DBNAME . "." . dbPerformance::COMPETITOINID . "=" . $competitionId;
    }

    private static function athlete(array $athleteIds)
    {
        $list = implode(",", $athleteIds);
        return " " . dbAthletes::DBNAME . "." . dbAthletes::ID  . " IN (" . $list . ")";
    }
}

