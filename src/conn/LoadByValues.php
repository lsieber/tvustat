<?php
namespace tvustat;

use config\dbAthletes;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbDisziplin;
use config\dbPerformance;
use config\dbTableDescription;

class LoadByValues extends DbHandler
{

    private function loadFromTableWhere(dbTableDescription $desc, string $whereKey, string $whereValue)
    {
        $sql = "SELECT * FROM " . $desc->getTableName() . " WHERE " . $whereKey . '="' . $whereValue . '"';
        $array = $this->conn->executeSqlToArray($sql);
        return (sizeof($array) == 1) ? $desc->array2Elmt($array[0], $this->conn) : NULL;
    }

    /**
     *
     * @param string $disziplinName
     * @return NULL|\tvustat\Disziplin
     */
    public function disziplin(string $disziplinName)
    {
        // $sql = "SELECT * FROM " . dbDisziplin::getTableName() . " WHERE " . dbDisziplin::NAME . "='" . $disziplinName . "'";
        // $array = $this->conn->executeSqlToArray($sql);
        // return (sizeof($array) == 0) ? NULL : dbDisziplin::array2Elmt($array[0], $this->conn);
        return self::loadFromTableWhere($this->getTable(dbDisziplin::class), dbDisziplin::NAME, $disziplinName);
    }

    /**
     *
     * @param string $athletename
     * @param \DateTime $birthdate
     * @return NULL|\tvustat\Athlete
     */
    public function athlete(string $athletename)
    {
        // $sql = "SELECT * FROM " . dbAthletes::getTableName() . " WHERE " . dbAthletes::FULLNAME . '="' . $athletename . '"'; /* '" AND ' . $birthDateSql; */
        // $array = $this->conn->executeSqlToArray($sql);
        // return (sizeof($array) == 0) ? NULL : dbAthletes::array2Elmt($array[0], $this->conn);
        return self::loadFromTableWhere($this->getTable(dbAthletes::class), dbAthletes::FULLNAME, $athletename);
    }

    /**
     *
     * @param string $saId
     * @return NULL|\tvustat\Athlete
     */
    public function athleteBySaId(string $saId)
    {
        // $sql = "SELECT * FROM " . dbAthletes::getTableName() . " WHERE " . dbAthletes::SAID . '="' . $saId . '"';
        // $array = $this->conn->executeSqlToArray($sql);
        // return (sizeof($array) == 0) ? NULL : dbAthletes::array2Elmt($array[0], $this->conn);
        return self::loadFromTableWhere($this->getTable(dbAthletes::class), dbAthletes::SAID, $saId);
    }

    /**
     *
     * @param int $licenseNumber
     * @return NULL|\tvustat\Athlete
     */
    public function athleteBySaLicense(int $licenseNumber)
    {
        // $sql = "SELECT * FROM " . dbAthletes::getTableName() . " WHERE " . dbAthletes::lICENCE . '=' . $licenseNumber;
        // echo "</br>" . $sql;
        // $array = $this->conn->executeSqlToArray($sql);
        // return (sizeof($array) == 0) ? NULL : dbAthletes::array2Elmt($array[0], $this->conn);
        return self::loadFromTableWhere($this->getTable(dbAthletes::class), dbAthletes::lICENCE, $licenseNumber);
    }

    /**
     *
     * @param string $competitionName
     * @return NULL|\tvustat\CompetitionName
     */
    public function competitionName(string $competitionName)
    {
        // $sql = "SELECT * FROM " . dbCompetitionNames::getTableName() . " WHERE " . dbCompetitionNames::NAME . '="' . $competitionName . '"';
        // // echo $sql;
        // $array = $this->conn->executeSqlToArray($sql);
        // return (sizeof($array) == 0) ? NULL : dbCompetitionNames::array2Elmt($array[0], $this->conn);
        return self::loadFromTableWhere($this->getTable(dbCompetitionNames::class), dbCompetitionNames::NAME, $competitionName);
    }

    /**
     *
     * @param string $competitionLocation
     * @return NULL|\tvustat\CompetitionLocation
     */
    public function competitionLocation(string $competitionLocation)
    {
        // $sql = "SELECT * FROM " . dbCompetitionLocations::getTableName() . " WHERE " . dbCompetitionLocations::VILLAGE . '="' . $competitionLocation . '"';
        // // echo $sql;
        // $array = $this->conn->executeSqlToArray($sql);
        // return (sizeof($array) == 0) ? NULL : dbCompetitionLocations::array2Elmt($array[0], $this->conn);
        return self::loadFromTableWhere($this->getTable(dbCompetitionLocations::class), dbCompetitionLocations::VILLAGE, $competitionLocation);
    }

    /**
     *
     * @param string $name
     * @param string $village
     * @param \DateTime $date
     * @return NULL|\tvustat\Disziplin
     */
    public function competition(string $name, string $village, \DateTime $date)
    {
        $sql = "SELECT * From " . dbCompetition::DBNAME;
        $sql .= " INNER JOIN " . dbCompetitionLocations::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::LOCATIONID . " = " . dbCompetitionLocations::DBNAME . "." . dbCompetitionLocations::ID;
        $sql .= " INNER JOIN " . dbCompetitionNames::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::NAMEID . " = " . dbCompetitionNames::DBNAME . "." . dbCompetitionNames::ID;
        $sql .= " WHERE " . dbCompetitionNames::NAME . '="' . $name . '" AND ' . dbCompetitionLocations::VILLAGE . '="' . $village . '" AND ' . dbCompetition::DATE . "='" . DateFormatUtils::formatDateForDB($date) . "'";
        // echo "</br>" . $sql;
        $array = $this->conn->executeSqlToArray($sql);
        return (sizeof($array) == 0) ? NULL : dbCompetition::array2Elmt($array[0], $this->conn);
    }

    public function performanceElmt(Performance $performance)
    {
        return $this->performance($performance->getAthlete()
            ->getId(), $performance->getDisziplin()
            ->getId(), $performance->getCompetition()
            ->getId(), $performance->getPerformance());
    }

    public function performance(int $athleteId, int $disziplinId, int $competitionId, float $performance, bool $alsoCheckWind = FALSE, float $wind = NULL, bool $alsoCheckRanking = FALSE, float $ranking = NULL)
    {
        $sql = "SELECT * FROM " . dbPerformance::DBNAME . " ";
        $sql .= PerformanceHelper::joins() . " ";
        $sql .= self::perfWhereSql($athleteId, $disziplinId, $competitionId, $performance);
        $sql .= ($alsoCheckWind) ? "AND " . dbPerformance::WIND . " = " . $wind . " " : "";
        $sql .= ($alsoCheckRanking) ? "AND " . dbPerformance::PLACE . " = " . $ranking . " " : "";

        $array = $this->conn->executeSqlToArray($sql);
        return (sizeof($array) > 0) ? dbPerformance::array2Elmt($array[0], $this->conn) : NULL;
    }

    private static function perfWhereSql(int $athleteId, int $disziplinId, int $competitionId, float $performance)
    {
        $sql = "WHERE " . dbPerformance::DBNAME . "." . dbPerformance::ATHLETEID . " = " . $athleteId . " AND ";
        $sql .= dbPerformance::DBNAME . "." . dbPerformance::DISZIPLINID . " = " . $disziplinId . " AND ";
        $sql .= dbPerformance::DBNAME . "." . dbPerformance::COMPETITOINID . " = " . $competitionId . " AND ";
        $sql .= dbPerformance::DBNAME . "." . dbPerformance::PERFORMANCE . " = " . $performance . " ";
        return $sql;
    }

    public function performanceAthleteYear(int $disziplinID, int $athleteID, int $year)
    {
        $sql = "SELECT * FROM " . dbPerformance::DBNAME;
        $sql .= " INNER JOIN " . dbCompetition::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::COMPETITOINID . " = " . dbCompetition::DBNAME . "." . dbCompetition::ID;
        $sql .= " WHERE " . dbPerformance::ATHLETEID . " = " . $athleteID;
        $sql .= " AND EXTRACT(YEAR FROM " . dbCompetition::DATE . ") = " . $year;
        $sql .= " AND " . dbPerformance::DISZIPLINID . " = " . $disziplinID;
        $sql .= " ORDER BY " . dbPerformance::PERFORMANCE;
        return $this->conn->executeSqlToArray($sql);
    }
}