<?php
namespace tvustat;

use config\dbAgeCategory;
use config\dbAthletes;
use config\dbBirthDateExeptions;
use config\dbCategory;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbConfig;
use config\dbDisziplin;
use config\dbOutputCategory;
use config\dbPerformance;
use config\dbPerformanceSource;
use config\dbPointSchemeNames;
use config\dbPointSchemes;
use config\dbMultipleDisziplins;
use config\dbAthleteActiveYear;
use config\dbUnsureBirthDates;

class DBMaintainer
{

    private $conn;

    private $config;

    private $add;

    private $check;

    private $getById;

    public $loadbyValues;

    public function __construct()
    {
        $this->conn = new ConnectionPreloaded();
        $this->config = new dbConfig();

        $this->add = new AddElement($this->conn, $this->config);
        $this->check = new CheckExistance($this->conn, $this->config);
        $this->getById = new GetByID($this->conn, $this->config);
        $this->loadbyValues = new LoadByValues($this->conn, $this->config);
    }

    /**
     * ADDING FUNCTIONALITIES
     */
    public function addAthlete(Athlete $athlete)
    {
        return $this->add->person($athlete);
    }

    public function addAthleteActiveYear(int $athleteID, int $athleteActiveYear)
    {
        $sqlActive = "INSERT INTO " . dbAthleteActiveYear::DBNAME . " VALUES (" . $athleteID . "," . $athleteActiveYear . ")";
        return $this->conn->getConn()->query($sqlActive);
    }

    public function addUnsureBirthDate(int $athleteId, bool $isUnsureDate, bool $isUnsureYear, int $minYear = null, int $maxYear = null) {
        $sqlUnsure = "INSERT INTO " . dbUnsureBirthDates::DBNAME . " VALUES (" . $athleteId . "," . intval($isUnsureDate) . "," .  intval($isUnsureYear). "," . self::nullToString($minYear) . "," .  self::nullToString($maxYear) . ")";
        return $this->conn->getConn()->query($sqlUnsure);
    }
    
    private static function nullToString($nullableValue = null){
        return ($nullableValue == null) ? "null": $nullableValue;
    }
    
    public function addDisziplin(Disziplin $disziplin)
    {
        return $this->add->disziplin($disziplin);
    }

    public function addCompetition(Competition $competition)
    {
        return $this->add->competition($competition);
    }

    public function addCompetitionName(CompetitionName $competitionName)
    {
        return $this->add->competitionName($competitionName);
    }

    /**
     *
     * @param CompetitionLocation $competitionLocation
     * @return \tvustat\QuerryOutcome
     */
    public function addCompetitionLocation(CompetitionLocation $competitionLocation)
    {
        return $this->add->competitionLocation($competitionLocation);
    }

    public function addPerformanceWithIdsOnly(array $associativeArray)
    {
        return $this->add->performanceWithIdsOnly($associativeArray);
    }

    /**
     *
     * @param Performance $performance
     * @return \tvustat\QuerryOutcome
     */
    public function addPerformance(Performance $performance)
    {
        $ids = array(
            dbPerformance::ATHLETEID => $performance->getAthlete()->getId(),
            dbPerformance::DISZIPLINID => $performance->getDisziplin()->getId(),
            dbPerformance::COMPETITOINID => $performance->getCompetition()->getId(),
            dbPerformance::PERFORMANCE => $performance->getPerformance(),
            dbPerformance::WIND => $performance->getWind(),
            dbPerformance::PLACE => $performance->getPlacement()
        );
        if (! $this->check->performanceByIds($ids)) {
            return $this->add->performance($performance);
        }
        return new QuerryOutcome("Value Already Exists", false);
    }

    /**
     * CHECKING FUNCTIONALITIES
     */

    /**
     *
     * @param Athlete $athlete
     * @return boolean
     */
    public function checkAthleteExists(Athlete $athlete)
    {
        return $this->check->athlete($athlete);
    }

    /**
     *
     * @param string $athleteName
     * @param \DateTime $birthDate
     * @return boolean
     */
    public function checkIfAlternativeBirthDate(string $athleteName, \DateTime $birthDate)
    {
        return dbBirthDateExeptions::isAthleteException($athleteName, $birthDate, $this->conn);
    }

    /**
     *
     * @param Disziplin $disziplin
     * @return boolean
     */
    public function checkDisziplinExists(Disziplin $disziplin)
    {
        return $this->check->disziplin($disziplin);
    }

    /**
     *
     * @param Competition $competition
     * @return boolean
     */
    public function checkCompetitionExists(Competition $competition)
    {
        return $this->check->competition($competition);
    }

    /**
     *
     * @param array $post
     * @return boolean
     */
    public function checkPerformanceByIds(array $post)
    {
        return $this->check->performanceByIds($post);
    }

    /**
     *
     * @param int $athleteId
     * @return NULL|boolean
     */
    public function checkAthleteIDExists(int $athleteId)
    {
        return $this->check->checkAthleteIDExists($athleteId);
    }

    /**
     *
     * @param int $competitionId
     * @return NULL|boolean
     */
    public function checkCompetitionIDExists(int $competitionId)
    {
        return $this->check->checkCompetitionIDExists($competitionId);
    }

    /**
     *
     * @param int $disziplinId
     * @return NULL|boolean
     */
    public function checkDisziplinIDExists(int $disziplinId)
    {
        return $this->check->checkDisziplinIDExists($disziplinId);
    }

    public function loadPerformanceAthleteYear(int $disziplinID, int $athleteID, int $year)
    {
        $sql = "SELECT * FROM " . dbPerformance::DBNAME;
        $sql .= " INNER JOIN " . dbCompetition::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::COMPETITOINID . " = " . dbCompetition::DBNAME . "." . dbCompetition::ID;
        $sql .= " WHERE " . dbPerformance::ATHLETEID . " = " . $athleteID;
        $sql .= " AND EXTRACT(YEAR FROM " . dbCompetition::DATE . ") = " . $year;
        $sql .= " AND " . dbPerformance::DISZIPLINID . " = " . $disziplinID;
        $sql .= " ORDER BY " . dbPerformance::PERFORMANCE;
        return $this->getConn()->executeSqlToArray($sql);
    }

    /**
     * GET BY ID
     */

    /**
     *
     * @param int $id
     * @return NULL|\tvustat\Performance
     */
    public function getPerformance(int $id)
    {
        return $this->getById->performance($id);
    }

    public function getAthlete(int $id)
    {
        return $this->getById->athlete($id);
    }

    public function getDisziplin(int $id)
    {
        return $this->getById->disziplin($id);
    }

    public function getCompetition(int $id)
    {
        return $this->getById->competition($id);
    }

    /**
     * GETTERS
     */

    /**
     *
     * @return \tvustat\ConnectionPreloaded
     */
    public function getConn()
    {
        return $this->conn;
    }

    /**
     * GET ALL FUNCTIONS
     */
    public function getAllCompetitions()
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

    public function getAllCompetitionLocations()
    {
        return $this->conn->executeSqlToArray("SELECT * From " . dbCompetitionLocations::DBNAME);
    }

    public function getAllCompetitionNames()
    {
        return $this->conn->executeSqlToArray("SELECT * From " . dbCompetitionNames::DBNAME);
    }

    public function getAllAgeCategories()
    {
        return $this->conn->executeSqlToArray("SELECT * From " . dbAgeCategory::DBNAME);
    }

    public function getAllCategories()
    {
        $sql = "SELECT * From " . dbCategory::DBNAME;
        $sql .= " INNER JOIN " . dbAgeCategory::DBNAME . " ON " . dbCategory::DBNAME . "." . dbCategory::AGECATEGORYID . " = " . dbAgeCategory::DBNAME . "." . dbAgeCategory::ID;
        return $this->conn->executeSqlToArray($sql . " ORDER BY " . dbCategory::ORDER);
    }

    public function getAllOutputCategories()
    {
        $sql = "SELECT * From " . dbOutputCategory::DBNAME;
        return $this->conn->executeSqlToArray($sql . " ORDER BY " . dbOutputCategory::ORDER);
    }

    public function getAllDisziplins()
    {
        $sql = "SELECT * From " . dbDisziplin::DBNAME;
        $sql .= " LEFT JOIN " . dbMultipleDisziplins::DBNAME . " ON " . dbMultipleDisziplins::DBNAME . "." . dbMultipleDisziplins::ID . " = " . dbDisziplin::DBNAME . "." . dbDisziplin::ID;

        // $sql .= " INNER JOIN " . dbAgeCategory::DBNAME . " ON " . dbCategory::DBNAME . "." . dbCategory::AGECATEGORYID . " = " . dbAgeCategory::DBNAME . "." . dbAgeCategory::ID;
        return $this->conn->executeSqlToArray($sql . " ORDER BY " . dbDisziplin::ORDER);
    }

    public function getAllAthletes()
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

    public function getAllYears()
    {
        $sql = "SELECT DISTINCT YEAR(" . dbCompetition::DATE . ") FROM " . dbPerformance::DBNAME;
        $sql .= " INNER JOIN " . dbCompetition::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::COMPETITOINID . " = " . dbCompetition::DBNAME . "." . dbCompetition::ID;
        $sql .= " ORDER BY YEAR(" . dbCompetition::DATE . ") DESC";
        return $this->conn->executeSqlToArray($sql);
    }

    public function getAllSources()
    {
        $sql = "SELECT * FROM " . dbPerformanceSource::DBNAME;
        return $this->conn->executeSqlToArray($sql);
    }

    public function getAllPointNameSchemes()
    {
        return $this->conn->executeSqlToArray("SELECT * From " . dbPointSchemeNames::DBNAME);
    }

    public function getAllPointSchemes()
    {
        $sql = "SELECT * From " . dbPointSchemes::DBNAME;
        $sql .= " INNER JOIN " . dbPointSchemeNames::DBNAME . " ON " . dbPointSchemes::DBNAME . "." . dbPointSchemes::COMPETITOINID . " = " . dbPointSchemeNames::DBNAME . "." . dbPointSchemeNames::ID;
        return $this->conn->executeSqlToArray($sql);
    }

    public function getPointScheme(int $genderId, int $pointSchemeNameId)
    {
        $sql = "SELECT * From " . dbPointSchemes::DBNAME;
        $sql .= " WHERE " . dbPointSchemes::GENDERID . "=" . $genderId . " AND " . dbPointSchemes::NAMEID . "=" . $pointSchemeNameId;
        return $this->conn->executeSqlToArray($sql);
    }

    /**
     * REMOVE
     */
    function removePerformance(int $id)
    {
        $sql = "DELETE FROM " . dbPerformance::DBNAME . " WHERE " . dbPerformance::ID . " = " . $id;
        return $this->conn->getConn()->query($sql);
    }
}

