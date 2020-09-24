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

    private function loadFromTableWhere(dbTableDescription $desc, string $whereKey, string $whereValue){
        $sql = "SELECT * FROM " . $desc->getTableName() . " WHERE " . $whereKey . '="' . $whereValue . '"';
        $array = $this->conn->executeSqlToArray($sql);
        return (sizeof($array) == 1) ? $desc.array2Elmt($array[0]) : NULL;
    }
    
    /**
     *
     * @param string $disziplinName
     * @return NULL|\tvustat\Disziplin
     */
    public function loadDiziplinByName(string $disziplinName)
    {
//         $sql = "SELECT * FROM " . dbDisziplin::getTableName() . " WHERE " . dbDisziplin::NAME . "='" . $disziplinName . "'";
//         $array = $this->conn->executeSqlToArray($sql);
//         return (sizeof($array) == 0) ? NULL : dbDisziplin::array2Elmt($array[0], $this->conn);
        return self::loadFromTableWhere($this->getTable(dbDisziplin::class), dbDisziplin::NAME, $disziplinName);
        
    }

    /**
     *
     * @param string $athletename
     * @param \DateTime $birthdate
     * @return NULL|\tvustat\Athlete
     */
    public function loadAthleteByName(string $athletename)
    {
//         $sql = "SELECT * FROM " . dbAthletes::getTableName() . " WHERE " . dbAthletes::FULLNAME . '="' . $athletename . '"'; /* '" AND ' . $birthDateSql; */
//         $array = $this->conn->executeSqlToArray($sql);
//         return (sizeof($array) == 0) ? NULL : dbAthletes::array2Elmt($array[0], $this->conn);
        return self::loadFromTableWhere($this->getTable(dbAthletes::class), dbAthletes::FULLNAME, $athletename);
    }


    /**
     *
     * @param string $saId
     * @return NULL|\tvustat\Athlete
     */
    public function loadAthleteBySaId(string $saId)
    {
//         $sql = "SELECT * FROM " . dbAthletes::getTableName() . " WHERE " . dbAthletes::SAID . '="' . $saId . '"';
//         $array = $this->conn->executeSqlToArray($sql);
//         return (sizeof($array) == 0) ? NULL : dbAthletes::array2Elmt($array[0], $this->conn);
        return self::loadFromTableWhere($this->getTable(dbAthletes::class), dbAthletes::SAID, $saId);
        
    }
    
    /**
     *
     * @param int $licenseNumber
     * @return NULL|\tvustat\Athlete
     */
    public function loadAthleteByLicense(int $licenseNumber)
    {
//         $sql = "SELECT * FROM " . dbAthletes::getTableName() . " WHERE " . dbAthletes::lICENCE . '=' . $licenseNumber;
//         echo "</br>" . $sql;
//         $array = $this->conn->executeSqlToArray($sql);
//         return (sizeof($array) == 0) ? NULL : dbAthletes::array2Elmt($array[0], $this->conn);
        return self::loadFromTableWhere($this->getTable(dbAthletes::class), dbAthletes::lICENCE, $licenseNumber);
        
    }

    /**
     *
     * @param string $competitionName
     * @return NULL|\tvustat\CompetitionName
     */
    public function loadCompetitionNameByName(string $competitionName)
    {
//         $sql = "SELECT * FROM " . dbCompetitionNames::getTableName() . " WHERE " . dbCompetitionNames::NAME . '="' . $competitionName . '"';
//         // echo $sql;
//         $array = $this->conn->executeSqlToArray($sql);
//         return (sizeof($array) == 0) ? NULL : dbCompetitionNames::array2Elmt($array[0], $this->conn);
        return self::loadFromTableWhere($this->getTable(dbCompetitionNames::class), dbCompetitionNames::NAME, $competitionName);
        
    }

    /**
     *
     * @param string $competitionLocation
     * @return NULL|\tvustat\CompetitionLocation
     */
    public function loadCompetitionLocationByName(string $competitionLocation)
    {
//         $sql = "SELECT * FROM " . dbCompetitionLocations::getTableName() . " WHERE " . dbCompetitionLocations::VILLAGE . '="' . $competitionLocation . '"';
//         // echo $sql;
//         $array = $this->conn->executeSqlToArray($sql);
//         return (sizeof($array) == 0) ? NULL : dbCompetitionLocations::array2Elmt($array[0], $this->conn);
        return self::loadFromTableWhere($this->getTable(dbCompetitionLocations::class), dbCompetitionLocations::VILLAGE, $competitionLocation);
        
    }

    /**
     *
     * @param string $name
     * @param string $village
     * @param \DateTime $date
     * @return NULL|\tvustat\Disziplin
     */
    public function loadCompetitionByName(string $name, string $village, \DateTime $date)
    {
        $sql = "SELECT * From " . dbCompetition::DBNAME;
        $sql .= " INNER JOIN " . dbCompetitionLocations::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::LOCATIONID . " = " . dbCompetitionLocations::DBNAME . "." . dbCompetitionLocations::ID;
        $sql .= " INNER JOIN " . dbCompetitionNames::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::NAMEID . " = " . dbCompetitionNames::DBNAME . "." . dbCompetitionNames::ID;
        $sql .= " WHERE " . dbCompetitionNames::NAME . '="' . $name . '" AND ' . dbCompetitionLocations::VILLAGE . '="' . $village . '" AND ' . dbCompetition::DATE . "='" . DateFormatUtils::formatDateForDB($date) . "'";
        // echo "</br>" . $sql;
        $array = $this->conn->executeSqlToArray($sql);
        return (sizeof($array) == 0) ? NULL : dbCompetition::array2Elmt($array[0], $this->conn);
    }

    public function loadPerformanceAthleteYear(int $disziplinID, int $athleteID, int $year)
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