<?php
namespace tvustat;

use config\dbAthletes;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbDisziplin;
use config\dbTableDescription;

class GetByID extends DbHandler
{

    const select = "SELECT * FROM ";

    public function athlete(int $id)
    {
        $r = self::getQuerryResult($this->getTable(dbAthletes::class), $id);
        return ($r == NULL) ? NULL : dbAthletes::athleteFromAsocArray($r, $this->conn);
    }

    public function disziplin(int $id)
    {
        $r = self::getQuerryResult($this->getTable(dbDisziplin::class), $id);
        return ($r == NULL) ? NULL : dbDisziplin::disziplinFromAsocArray($r, $this->conn);
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
        return ($r == NULL) ? NULL : dbCompetition::competitionFromAsocArray($r, $this->conn);
    }

    // private function copetitionFromAsocArray($r)
    // {
    // return new Competition( //
    // $r[dbAthletes::FULLNAME], //
    // $r[dbAthletes::LASTNAME], //
    // new \DateTime($r[dbAthletes::DATE]), //
    // $this->conn->getGender($r[dbAthletes::GENDERID]), //
    // $this->conn);
    // }
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

