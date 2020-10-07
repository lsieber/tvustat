<?php
namespace tvustat;

use config\dbAgeCategory;
use config\dbAthleteActiveYear;
use config\dbAthletes;
use config\dbCategory;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbDisziplin;
use config\dbMultipleDisziplins;
use config\dbOutputCategory;
use config\dbPerformance;
use config\dbPerformanceSource;
use config\dbPointSchemeNames;
use config\dbPointSchemes;

class GetAll extends DbHandler
{

    /**
     * GET ALL FUNCTIONS
     */
    public function competitions()
    {
        $sql = self::getCompetitionSQl();
        return self::changeDateType($this->conn->executeSqlToArray($sql), dbCompetition::DATE);
    }

    private static function getCompetitionSQl()
    {
        $sql = "SELECT * From " . dbCompetition::DBNAME;
        $sql .= " INNER JOIN " . dbCompetitionLocations::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::LOCATIONID . " = " . dbCompetitionLocations::DBNAME . "." . dbCompetitionLocations::ID;
        $sql .= " INNER JOIN " . dbCompetitionNames::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::NAMEID . " = " . dbCompetitionNames::DBNAME . "." . dbCompetitionNames::ID;
        return $sql;
    }

    // ?????????????????????????????
    public function getCompetitionsForYear(array $years)
    {
        $sql = self::getCompetitionSQl();
        $list = implode(",", $years);
        $sql .= " WHERE EXTRACT(YEAR FROM " . dbCompetition::DATE . ") IN (" . $list . ") ORDER BY " . dbCompetition::DATE;
        $r = self::changeDateType($this->conn->executeSqlToArray($sql), dbCompetition::DATE);

        foreach ($r as $k => $v) {
            $r[$k]["numberPerformances"] = self::countNumberPerformancesForCompetition($v[dbCompetition::ID]);
        }

        return $r;
    }

    private function countNumberPerformancesForCompetition(int $competitionID)
    {
        $sql = 'SELECT COUNT(*) FROM ' . dbPerformance::DBNAME . ' WHERE ' . dbPerformance::COMPETITOINID . ' = ' . $competitionID;
        return $this->conn->executeSqlToArray($sql)[0]["COUNT(*)"];
    }

    private function countNumberPerformancesForCompetitionAndCat(int $competitionID, AgeCategory $ageCat)
    {
        $sql = 'SELECT COUNT(*) FROM ' . dbPerformance::DBNAME . ' WHERE ' . dbPerformance::COMPETITOINID . ' = ' . $competitionID;
        return $this->conn->executeSqlToArray($sql)[0]["COUNT(*)"];
    }

    // ?????????????????????????????
    private function getAllElmts($dbName)
    {
        return $this->conn->executeSqlToArray("SELECT * From " . $dbName);
    }

    public function competitionLocations()
    {
        return $this->getAllElmts(dbCompetitionLocations::DBNAME);
    }

    public function competitionNames()
    {
        return $this->getAllElmts(dbCompetitionNames::DBNAME);
    }

    public function ageCategories()
    {
        return $this->getAllElmts(dbAgeCategory::DBNAME);
    }

    public function categories()
    {
        $sql = "SELECT * From " . dbCategory::DBNAME;
        $sql .= " INNER JOIN " . dbAgeCategory::DBNAME . " ON " . dbCategory::DBNAME . "." . dbCategory::AGECATEGORYID . " = " . dbAgeCategory::DBNAME . "." . dbAgeCategory::ID;
        return $this->conn->executeSqlToArray($sql . " ORDER BY " . dbCategory::ORDER);
    }

    public function outputCategories()
    {
        $sql = "SELECT * From " . dbOutputCategory::DBNAME;
        return $this->conn->executeSqlToArray($sql . " ORDER BY " . dbOutputCategory::ORDER);
    }
    public function disziplinsClasses(){
        $disziplins = array();
        foreach( $this->disziplins() as $d){
            $disziplins[$d[dbDisziplin::getIDString()]] = dbDisziplin::array2Elmt($d, $this->conn);
        }
        return $disziplins;
    }
    
    public function disziplins()
    {
        $sql = "SELECT * From " . dbDisziplin::DBNAME;
        $sql .= " LEFT JOIN " . dbMultipleDisziplins::DBNAME . " ON " . dbMultipleDisziplins::DBNAME . "." . dbMultipleDisziplins::ID . " = " . dbDisziplin::DBNAME . "." . dbDisziplin::ID;
        return $this->conn->executeSqlToArray($sql . " ORDER BY " . dbDisziplin::ORDER);
    }

    public function athletes()
    {
        $innerjoin = " LEFT JOIN " . dbAthleteActiveYear::DBNAME . " ON " . dbAthleteActiveYear::DBNAME . "." . dbAthleteActiveYear::ID . " = " . dbAthletes::DBNAME . "." . dbAthletes::ID;
        $r = $this->conn->executeSqlToArray("SELECT * From " . dbAthletes::DBNAME . $innerjoin . " ORDER BY " . dbAthletes::FULLNAME);
        return self::changeDateType($r, dbAthletes::DATE);
    }

    private static function changeDateType(array $r, $identifier)
    {
        foreach ($r as $key => $entry) {
            $r[$key][$identifier] = DateFormatUtils::convertDateFromDB2BL($entry[$identifier]);
        }
        return $r;
    }

    public function years()
    {
        $sql = "SELECT DISTINCT YEAR(" . dbCompetition::DATE . ") FROM " . dbPerformance::DBNAME;
        $sql .= " INNER JOIN " . dbCompetition::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::COMPETITOINID . " = " . dbCompetition::DBNAME . "." . dbCompetition::ID;
        $sql .= " ORDER BY YEAR(" . dbCompetition::DATE . ") DESC";
        return $this->conn->executeSqlToArray($sql);
    }

    public function sources()
    {
        return $this->getAllElmts(dbPerformanceSource::DBNAME);
    }

    public function pointNameSchemes()
    {
        return $this->getAllElmts( dbPointSchemeNames::DBNAME);
    }

    public function pointSchemes()
    {
        $sql = "SELECT * From " . dbPointSchemes::DBNAME;
        $sql .= " INNER JOIN " . dbPointSchemeNames::DBNAME . " ON " . dbPointSchemes::DBNAME . "." . dbPointSchemes::COMPETITOINID . " = " . dbPointSchemeNames::DBNAME . "." . dbPointSchemeNames::ID;
        return $this->conn->executeSqlToArray($sql);
    }

    public function pointScheme(int $genderId, int $pointSchemeNameId)
    {
        $sql = "SELECT * From " . dbPointSchemes::DBNAME;
        $sql .= " WHERE " . dbPointSchemes::GENDERID . "=" . $genderId . " AND " . dbPointSchemes::NAMEID . "=" . $pointSchemeNameId;
        return $this->conn->executeSqlToArray($sql);
    }
}