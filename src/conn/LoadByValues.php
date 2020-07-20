<?php
namespace tvustat;

use config\dbAthletes;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbDisziplin;

class LoadByValues extends DbHandler
{

    /**
     *
     * @param string $disziplinName
     * @return NULL|\tvustat\Disziplin
     */
    public function loadDiziplinByName(string $disziplinName)
    {
        $sql = "SELECT * FROM " . dbDisziplin::getTableName() . " WHERE " . dbDisziplin::NAME . "='" . $disziplinName . "'";
        $array = $this->conn->executeSqlToArray($sql);
        return (sizeof($array) == 0) ? NULL : dbDisziplin::array2Elmt($array[0], $this->conn);
    }

    /**
     *
     * @param string $athletename
     * @param \DateTime $birthdate
     * @return NULL|\tvustat\Athlete
     */
    public function loadAthleteByName(string $athletename)
    {
        // $birthDateSql = (is_null($birthdate)) ? dbAthletes::DATE . " IS NULL" : dbAthletes::DATE . "='" . DateFormatUtils::formatDateForDB($birthdate) . "'";
        $sql = "SELECT * FROM " . dbAthletes::getTableName() . " WHERE " . dbAthletes::FULLNAME . '="' . $athletename . '"'; /* '" AND ' . $birthDateSql; */
        // echo "</br>" . $sql;
        $array = $this->conn->executeSqlToArray($sql);
        return (sizeof($array) == 0) ? NULL : dbAthletes::array2Elmt($array[0], $this->conn);
    }
    /**
     *
     * @param int $licenseNumber
     * @return NULL|\tvustat\Athlete
     */
    public function loadAthleteByLicense(int $licenseNumber)
    {
        $sql = "SELECT * FROM " . dbAthletes::getTableName() . " WHERE " . dbAthletes::lICENCE . '=' . $licenseNumber; 
        echo "</br>" . $sql;
        $array = $this->conn->executeSqlToArray($sql);
        return (sizeof($array) == 0) ? NULL : dbAthletes::array2Elmt($array[0], $this->conn);
    }
    
    /**
     *
     * @param string $competitionName
     * @return NULL|\tvustat\CompetitionName
     */
    public function loadCompetitionNameByName(string $competitionName)
    {
        $sql = "SELECT * FROM " . dbCompetitionNames::getTableName() . " WHERE " . dbCompetitionNames::NAME . '="' . $competitionName . '"';
        // echo $sql;
        $array = $this->conn->executeSqlToArray($sql);
        return (sizeof($array) == 0) ? NULL : dbCompetitionNames::array2Elmt($array[0], $this->conn);
    }

    /**
     *
     * @param string $competitionLocation
     * @return NULL|\tvustat\CompetitionLocation
     */
    public function loadCompetitionLocationByName(string $competitionLocation)
    {
        $sql = "SELECT * FROM " . dbCompetitionLocations::getTableName() . " WHERE " . dbCompetitionLocations::VILLAGE . '="' . $competitionLocation . '"';
        // echo $sql;
        $array = $this->conn->executeSqlToArray($sql);
        return (sizeof($array) == 0) ? NULL : dbCompetitionLocations::array2Elmt($array[0], $this->conn);
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
}