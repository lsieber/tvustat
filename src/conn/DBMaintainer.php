<?php
namespace tvustat;

use config\dbAgeCategory;
use config\dbConfig;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbCompetition;
use config\dbCategory;

class DBMaintainer
{

    private $conn;

    private $config;

    private $add;

    private $check;

    private $getById;

    public function __construct()
    {
        $this->conn = new ConnectionPreloaded();
        $this->config = new dbConfig();

        $this->add = new AddElement($this->conn, $this->config);
        $this->check = new CheckExistance($this->conn, $this->config);
        $this->getById = new GetByID($this->conn, $this->config);
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

    public function addCompetitionLocation(CompetitionLocation $competitionLocation)
    {
        return $this->add->competitionLocation($competitionLocation);
    }

    public function addPerformanceWithIdsOnly(array $associativeArray){
        return $this->add->performanceWithIdsOnly($associativeArray);
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
     * @param int $athleteId
     * @return NULL|boolean
     */
    public function checkAthleteIDExists(int $athleteId){
        return $this->check->checkAthleteIDExists($athleteId);
    }
    
    /**
     * 
     * @param int $competitionId
     * @return NULL|boolean
     */
    public function checkCompetitionIDExists(int $competitionId){
        return $this->check->checkCompetitionIDExists($competitionId);
    }
    
    /**
     * 
     * @param int $disziplinId
     * @return NULL|boolean
     */
    public function checkDisziplinIDExists(int $disziplinId){
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
    
    public function getAllCompetitions() {
        $sql = "SELECT * From " . dbCompetition::DBNAME;
        $sql .= " INNER JOIN ".dbCompetitionLocations::DBNAME." ON ".dbCompetition::DBNAME.".".dbCompetition::LOCATIONID." = ".dbCompetitionLocations::DBNAME.".".dbCompetitionLocations::ID;
        $sql .= " INNER JOIN ".dbCompetitionNames::DBNAME." ON ".dbCompetition::DBNAME.".".dbCompetition::NAMEID." = ".dbCompetitionNames::DBNAME.".".dbCompetitionNames::ID;
        return $this->conn->executeSqlToArray($sql);
    }
    
    public function getAllCompetitionLocations() {
        return $this->conn->executeSqlToArray("SELECT * From " . dbCompetitionLocations::DBNAME);
    }
    
    public function getAllCompetitionNames() {
        return $this->conn->executeSqlToArray("SELECT * From " . dbCompetitionNames::DBNAME);
    }
    
    public function getAllAgeCategories() {
        return $this->conn->executeSqlToArray("SELECT * From " . dbAgeCategory::DBNAME);
    }
    public function getAllCategories() {
        return $this->conn->executeSqlToArray("SELECT * From " . dbCategory::DBNAME);
    }
}

