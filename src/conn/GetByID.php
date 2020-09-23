<?php
namespace tvustat;

use config\dbAthletes;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbDisziplin;
use config\dbPerformance;
use config\dbPerformanceDetail;
use config\dbTableDescription;

class GetByID extends DbHandler
{

    const select = "SELECT * FROM ";

    /**
     * 
     * @param int $id
     * @return NULL|\tvustat\Performance
     */
    public function performance(int $id)
    {
        $sql = "SELECT * FROM " . dbPerformance::DBNAME;

        $sql .= " INNER JOIN " . dbCompetition::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::COMPETITOINID . " = " . dbCompetition::DBNAME . "." . dbCompetition::ID;
        $sql .= " INNER JOIN " . dbCompetitionLocations::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::LOCATIONID . " = " . dbCompetitionLocations::DBNAME . "." . dbCompetitionLocations::ID;
        $sql .= " INNER JOIN " . dbCompetitionNames::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::NAMEID . " = " . dbCompetitionNames::DBNAME . "." . dbCompetitionNames::ID . " ";

        $sql .= " INNER JOIN " . dbDisziplin::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::DISZIPLINID . " = " . dbDisziplin::DBNAME . "." . dbDisziplin::ID;
        $sql .= " INNER JOIN " . dbAthletes::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::ATHLETEID . " = " . dbAthletes::DBNAME . "." . dbAthletes::ID;

        $sql .= " LEFT JOIN " . dbPerformanceDetail::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::ID . " = " . dbPerformanceDetail::DBNAME . "." . dbPerformanceDetail::PERFORMANCEID;
        $sql .= " WHERE " . dbPerformance::DBNAME . "." . dbPerformance::ID . " = " . $id;
        $r = $this->conn->executeSqlToArray($sql);
        return ($r == NULL) ? NULL : dbPerformance::array2Elmt($r[0], $this->conn);
    }

    /**
     * 
     * @param int $id
     * @return NULL|\tvustat\Athlete
     */
    public function athlete(int $id)
    {
        $r = self::getQuerryResult($this->getTable(dbAthletes::class), $id);
        return ($r == NULL) ? NULL : dbAthletes::array2Elmt($r, $this->conn);
    }

    /**
     * 
     * @param int $id
     * @return NULL|\tvustat\Disziplin
     */
    public function disziplin(int $id)
    {
        $r = self::getQuerryResult($this->getTable(dbDisziplin::class), $id);
        return ($r == NULL) ? NULL : dbDisziplin::array2Elmt($r, $this->conn);
    }

    /**
     *
     * @param int $id
     * @return NULL|\tvustat\Competition
     */
    public function competition(int $id)
    {
        $join = " INNER JOIN " . dbCompetitionLocations::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::LOCATIONID . " = " . dbCompetitionLocations::DBNAME . "." . dbCompetitionLocations::ID;
        $join .= " INNER JOIN " . dbCompetitionNames::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::NAMEID . " = " . dbCompetitionNames::DBNAME . "." . dbCompetitionNames::ID . " ";
        $r = self::getQuerryResult($this->getTable(dbCompetition::class), $id, $join);
        return ($r == NULL) ? NULL : dbCompetition::array2Elmt($r, $this->conn);
    }

    private function getQuerryResult(dbTableDescription $desc, int $id, $innerJoins = NULL)
    {
        $idString = $desc->getIDString();
        $table = $desc->getTableName();

        $join = (is_null($innerJoins)) ? "" : $innerJoins;

        $sql = self::select . $table . $join . " WHERE " . $idString . "=" . $id;
        // echo $sql;
        $r = $this->conn->executeSqlToArray($sql);

        if (sizeof($r) != 1) {
            if (sizeof($r) > 1) {
                echo "Multiple Elements hit";
            }
            return NUll;
        }
        return $r[0];
    }
}

