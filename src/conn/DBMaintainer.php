<?php
namespace tvustat;

use config\dbConfig;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;

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
     * GET BY ID
     */
    public function getPerson(int $id)
    {
        return $this->getById->athlete($id);
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
    
    public function getAllCompetitionLocations() {
        return $this->conn->executeSqlToArray("SELECT * From " . dbCompetitionLocations::DBNAME);
    }
    
    public function getAllCompetitionNames() {
        return $this->conn->executeSqlToArray("SELECT * From " . dbCompetitionNames::DBNAME);
    }
}

