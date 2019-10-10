<?php
namespace tvustat;

use config\dbAgeCategory;
use config\dbConfig;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbCompetition;
use config\dbCategory;
use config\dbDisziplin;
use config\dbAthletes;
use config\dbPerformance;
use config\dbBirthDateExeptions;

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
        if (!$this->check->performanceByIds($ids)) {
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
    public function checkIfAlternativeBirthDate(string $athleteName, \DateTime $birthDate){
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
    
    /**
     * GET BY ID
     */
    public function getAthlete(int $id)
    {
        return $this->getById->athlete($id);
    }

    public function getDisziplin(int $id)
    {
        return $this->getById->disziplin($id);
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
       return self::changeDateType($this->conn->executeSqlToArray($sql),dbCompetition::DATE);
    }
    
    private static function getCompetitionSQl(){
        $sql = "SELECT * From " . dbCompetition::DBNAME;
        $sql .= " INNER JOIN " . dbCompetitionLocations::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::LOCATIONID . " = " . dbCompetitionLocations::DBNAME . "." . dbCompetitionLocations::ID;
        $sql .= " INNER JOIN " . dbCompetitionNames::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::NAMEID . " = " . dbCompetitionNames::DBNAME . "." . dbCompetitionNames::ID;
        return $sql;
    }
    
    public function getCompetitionsForYear(array $years){
        $sql = self::getCompetitionSQl();
        $list = implode(",", $years);
        $sql .= " EXTRACT(YEAR FROM " . dbCompetition::DATE . ") IN (" . $list . ")";
        echo $sql;
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

    public function getAllDisziplins()
    {
        $sql = "SELECT * From " . dbDisziplin::DBNAME;
        // $sql .= " INNER JOIN " . dbAgeCategory::DBNAME . " ON " . dbCategory::DBNAME . "." . dbCategory::AGECATEGORYID . " = " . dbAgeCategory::DBNAME . "." . dbAgeCategory::ID;
        return $this->conn->executeSqlToArray($sql . " ORDER BY " . dbDisziplin::ORDER);
    }
    
    public function getAllAthletes()
    {
        $r = $this->conn->executeSqlToArray("SELECT * From " . dbAthletes::DBNAME . " ORDER BY " . dbAthletes::FULLNAME);
        return self::changeDateType($r,dbAthletes::DATE);
    }
    
    private static function changeDateType(array $r, $identifier){
        foreach ($r as $key=> $entry) {
            $r[$key][$identifier] = DateFormatUtils::convertDateFromDB2BL($entry[$identifier]);
        }
        return $r;
    }
    
    public function getAllYears(){
        $sql = "SELECT DISTINCT YEAR(".dbCompetition::DATE.") FROM ". dbPerformance::DBNAME ; 
        $sql .= " INNER JOIN " . dbCompetition::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::COMPETITOINID . " = " . dbCompetition::DBNAME . "." . dbCompetition::ID;
        return $this->conn->executeSqlToArray($sql);
    }
}

