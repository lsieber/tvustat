<?php
namespace tvustat;

use config\dbAthletes;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbDisziplin;
use config\dbPerformance;
use config\dbTableDescription;
use function tvustat\GetByID\getByIdWhere;

/**
 * Gets an Element of the Database by its id.
 * If the function returns NULL, no Element could be found
 *
 * @author lukas
 *        
 */
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

        $sql .= " " . PerformanceHelper::joins();

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
        $r = self::getElmtById($this->getTable(dbAthletes::class), $id);
        return ($r == NULL) ? NULL : dbAthletes::array2Elmt($r, $this->conn);
    }

    /**
     *
     * @param array $ids
     *            Array of ints
     * @return NULL|\tvustat\Athlete
     */
    public function athletes(array $ids)
    {
        $athletes = array();
        foreach ($ids as $id) {
            array_push($athletes, $this->athlete($id));
        }
        return $athletes;
    }

    /**
     *
     * @param int $id
     * @return NULL|\tvustat\Disziplin
     */
    public function disziplin(int $id)
    {
        $r = self::getElmtById($this->getTable(dbDisziplin::class), $id);
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
        $r = self::getElmtById($this->getTable(dbCompetition::class), $id, $join);
        return ($r == NULL) ? NULL : dbCompetition::array2Elmt($r, $this->conn);
    }

    private function getElmtById(dbTableDescription $desc, int $id, $innerJoins = NULL)
    {
        $table = $desc->getTableName();
        return $this->getByIdWhere($table, $desc->getIDString(), $id, $innerJoins);
    }

    private function getByIdWhere($table, string $whereKey, string $whereValue, $innerJoins = NULL)
    {
        $join = (is_null($innerJoins)) ? "" : $innerJoins;
        $sql = self::select . $table . $join . " WHERE " . $whereKey . "=" . $whereValue;
        //echo $sql;
        $r = $this->conn->executeSqlToArray($sql);

        if (sizeof($r) != 1) {
            if (sizeof($r) > 1) {
                echo "Multiple Elements hit while searching: " . $sql;
            }
            return NUll;
        }
        return $r[0];
    }
}

