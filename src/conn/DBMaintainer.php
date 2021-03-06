<?php
namespace tvustat;

use config\dbAthleteActiveYear;
use config\dbAthletes;
use config\dbConfig;
use config\dbUnsureBirthDates;

class DBMaintainer
{

    private $conn;

    private $config;

    public $add;

    // private $check;
    public $getById;

    public $getAll;

    private $delete;

    public $getbyValues;
    
    public $log;

    public function __construct()
    {
        $this->conn = new ConnectionPreloaded();
        $this->config = new dbConfig();

        $this->add = new AddElement($this->conn, $this->config);
        // $this->check = new CheckExistance($this->conn, $this->config);
        $this->getById = new GetByID($this->conn, $this->config);
        $this->getbyValues = new LoadByValues($this->conn, $this->config);
        $this->getAll = new GetAll($this->conn, $this->config);
        $this->delete = new Delete($this->conn, $this->config);
        $this->log = new Log($this->conn, $this->config);
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
        return $this->add->athleteActiveYear($athleteID, $athleteActiveYear);
    }

    public function addUnsureBirthDate(int $athleteId, bool $isUnsureDate, bool $isUnsureYear, int $minYear = null, int $maxYear = null)
    {
        return $this->add->unsureBirthDate($athleteId, $isUnsureDate, $isUnsureYear);
    }

    public function addSaIdToAthlete(Athlete $athlete, string $saId)
    {
        return $this->add->saIdToAthlete($athlete, $saId);
    }

    public function addLicenseToAthlete(Athlete $athlete, string $license)
    {
        return $this->add->licenseToAthlete($athlete, $license);
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

//     public function addPerformanceWithIdsOnly(array $associativeArray)
//     {
//         return $this->add->performanceWithIdsOnly($associativeArray);
//     }

    /**
     *
     * @param Performance $performance
     * @return \tvustat\QuerryOutcome
     */
    public function addPerformance(Performance $performance)
    {
        // TODO Move To Add
        return $this->add->performance($performance);
    }

    /**
     * CHECKING FUNCTIONALITIES
     */

    // /**
    // *
    // * @param Athlete $athlete
    // * @return boolean
    // */
    // public function checkAthleteExists(Athlete $athlete)
    // {
    // return $this->check->athlete($athlete);
    // }

    // /**
    // *
    // * @param string $athleteName
    // * @param \DateTime $birthDate
    // * @return boolean
    // */
    // public function checkIfAlternativeBirthDate(string $athleteName, \DateTime $birthDate)
    // {
    // return dbBirthDateExeptions::isAthleteException($athleteName, $birthDate, $this->conn);
    // }

    // /**
    // *
    // * @param Disziplin $disziplin
    // * @return boolean
    // */
    // public function checkDisziplinExists(Disziplin $disziplin)
    // {
    // return $this->check->disziplin($disziplin);
    // }

    // /**
    // *
    // * @param Competition $competition
    // * @return boolean
    // */
    // public function checkCompetitionExists(Competition $competition)
    // {
    // return $this->check->competition($competition);
    // }

    // /**
    // *
    // * @param array $post
    // * @return boolean
    // */
    // public function checkPerformanceByIds(array $post)
    // {
    // return $this->check->performanceByIdsArray($post);
    // }

    // /**
    // *
    // * @param int $athleteId
    // * @return NULL|boolean
    // */
    // public function checkAthleteIDExists(int $athleteId)
    // {
    // return $this->check->checkAthleteIDExists($athleteId);
    // }

    // /**
    // *
    // * @param int $competitionId
    // * @return NULL|boolean
    // */
    // public function checkCompetitionIDExists(int $competitionId)
    // {
    // return $this->check->checkCompetitionIDExists($competitionId);
    // }

    // /**
    // *
    // * @param int $disziplinId
    // * @return NULL|boolean
    // */
    // public function checkDisziplinIDExists(int $disziplinId)
    // {
    // return $this->check->checkDisziplinIDExists($disziplinId);
    // }
    public function loadPerformanceAthleteYear(int $disziplinID, int $athleteID, int $year)
    {
        return $this->getbyValues->performanceAthleteYear($disziplinID, $athleteID, $year);
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

    public function getAthletes(array $ids)
    {
        return $this->getById->athletes($ids);
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
        return $this->getAll->competitions();
    }

    // ?????????????????????????????
    public function getCompetitionsForYear(array $years)
    {
        return $this->getAll->getCompetitionsForYear($years);
    }

    public function getAllCompetitionLocations()
    {
        return $this->getAll->competitionLocations();
    }

    public function getAllCompetitionNames()
    {
        return $this->getAll->competitionNames();
    }

    public function getAllAgeCategories()
    {
        return $this->getAll->ageCategories();
    }

    public function getAllCategories()
    {
        return $this->getAll->categories();
    }

    public function getAllOutputCategories()
    {
        return $this->getAll->outputCategories();
    }

    public function getAllDisziplins()
    {
        return $this->getAll->disziplins();
    }

    public function getAllAthletes()
    {
        return $this->getAll->athletes();
    }

    public function getAllYears()
    {
        return $this->getAll->years();
    }

    public function getAllSources()
    {
        return $this->getAll->sources();
    }

    public function getAllPointNameSchemes()
    {
        return $this->getAll->pointNameSchemes();
    }

    public function getAllPointSchemes()
    {
        return $this->getAll->pointSchemes();
    }

    public function getPointScheme(int $genderId, int $pointSchemeNameId)
    {
        return $this->getAll->pointScheme($genderId, $pointSchemeNameId);
    }

    /**
     * REMOVE
     */
    function removePerformance(int $id)
    {
        return $this->delete->performance($id);
    }

    /**
     * Specials
     */
    public function athletesForCategory($year, $categories)
    {
        $sql = "SELECT * FROM " . dbAthletes::DBNAME;
        $sql .= " LEFT JOIN " . dbAthleteActiveYear::DBNAME . " ON " . dbAthleteActiveYear::DBNAME . "." . dbAthleteActiveYear::ID . " = " . dbAthletes::DBNAME . "." . dbAthletes::ID;
        $sql .= " LEFT JOIN " . dbUnsureBirthDates::DBNAME . " ON " . dbUnsureBirthDates::DBNAME . "." . dbUnsureBirthDates::ID . " = " . dbAthletes::DBNAME . "." . dbAthletes::ID;

        $sql .= " WHERE (";
        $first = true;
        foreach ($_POST["categories"] as $id) {
            if (! $first) {
                $sql .= " OR";
            }
            $category = $this->getConn()->getCategory($id);
            $sql .= " (" . $year . " - EXTRACT(YEAR FROM " . dbAthletes::DBNAME . "." . dbAthletes::DATE . ") >= " . $category->getAgeCategory()->getMinAge() . " AND";
            $sql .= " " . $year . " - EXTRACT(YEAR FROM " . dbAthletes::DBNAME . "." . dbAthletes::DATE . ") <= " . $category->getAgeCategory()->getMaxAge() . " AND ";
            $sql .= dbAthletes::GENDERID . " = " . $category->getGender()->getId() . ")";
            $first = false;
        }
        $sql .= ") OR (" . dbAthletes::CATEGORY . " IN (" . implode(",", $categories) . ") ) ORDER BY " . dbAthletes::TEAMTYPEID . "," . dbAthletes::FULLNAME;
        return $this->getConn()->executeSqlToArray($sql);
    }
    
    public function similarAthletes($namepart) {
        $sql = "SELECT * FROM " . dbAthletes::DBNAME;
        $sql .= " LEFT JOIN " . dbAthleteActiveYear::DBNAME . " ON " . dbAthleteActiveYear::DBNAME . "." . dbAthleteActiveYear::ID . " = " . dbAthletes::DBNAME . "." . dbAthletes::ID;
        $sql .= " LEFT JOIN " . dbUnsureBirthDates::DBNAME . " ON " . dbUnsureBirthDates::DBNAME . "." . dbUnsureBirthDates::ID . " = " . dbAthletes::DBNAME . "." . dbAthletes::ID;
        
        $sql .= " WHERE " . dbAthletes::FULLNAME . " LIKE '%" . $namepart . "%' ORDER BY " . dbAthletes::FULLNAME;
        return $this->getConn()->executeSqlToArray($sql);
    }
}

