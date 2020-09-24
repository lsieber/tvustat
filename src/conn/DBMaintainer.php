<?php
namespace tvustat;

use config\dbAthleteActiveYear;
use config\dbAthletes;
use config\dbBirthDateExeptions;
use config\dbConfig;
use config\dbPerformance;
use config\dbUnsureBirthDates;

class DBMaintainer
{

    private $conn;

    private $config;

    private $add;

    private $check;

    private $getById;

    private $getAll;

    private $delete;

    public $loadbyValues;

    public function __construct()
    {
        $this->conn = new ConnectionPreloaded();
        $this->config = new dbConfig();

        $this->add = new AddElement($this->conn, $this->config);
        $this->check = new CheckExistance($this->conn, $this->config);
        $this->getById = new GetByID($this->conn, $this->config);
        $this->loadbyValues = new LoadByValues($this->conn, $this->config);
        $this->getAll = new GetAll($this->conn, $this->config);
        $this->delete = new Delete($this->conn, $this->config);
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
        // TODO Move To Add
        $sqlActive = "INSERT INTO " . dbAthleteActiveYear::DBNAME . " VALUES (" . $athleteID . "," . $athleteActiveYear . ")";
        return $this->conn->getConn()->query($sqlActive);
    }

    public function addUnsureBirthDate(int $athleteId, bool $isUnsureDate, bool $isUnsureYear, int $minYear = null, int $maxYear = null)
    {
        // TODO Move To Add
        $sqlUnsure = "INSERT INTO " . dbUnsureBirthDates::DBNAME . " VALUES (" . $athleteId . "," . intval($isUnsureDate) . "," . intval($isUnsureYear) . "," . self::nullToString($minYear) . "," . self::nullToString($maxYear) . ")";
        return $this->conn->getConn()->query($sqlUnsure);
    }

    public function addSaIdToAthlete(Athlete $athlete, string $saId)
    {
        // TODO Move To Add
        $athleteId = $athlete->getId();
        $athleteDb = $this->loadbyValues->loadAthleteByName($athlete->getFullName());
        if ($athleteId == $athleteDb->getId() && is_null($athleteDb->getSaId())) {
            $sqlUpdate = "UPDATE `athletes` SET " . dbAthletes::SAID . " = '" . $saId . "' WHERE athleteID = " . $athleteId;
            return $this->conn->getConn()->query($sqlUpdate);
        }
        return null;
    }

    public function addLicenseToAthlete(Athlete $athlete, string $license)
    {
        // TODO Move To Add
        $athleteId = $athlete->getId();
        $athleteDb = $this->loadbyValues->loadAthleteByName($athlete->getFullName());
        if ($athleteId == $athleteDb->getId() && is_null($athleteDb->getLicenseNumber())) {
            $sqlUpdate = "UPDATE `athletes` SET " . dbAthletes::lICENCE . " = " . $license . " WHERE athleteID = " . $athleteId;
            return $this->conn->getConn()->query($sqlUpdate);
        }
        return null;
    }

    private static function nullToString($nullableValue = null)
    {
        return ($nullableValue == null) ? "null" : $nullableValue;
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
        // TODO Move To Add
        return (! $this->check->performanceByIds($performance)) ? $this->add->performance($performance) : new QuerryOutcome("Value Already Exists", false);
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
        return $this->check->performanceByIdsArray($post);
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
        return $this->loadbyValues->loadPerformanceAthleteYear($disziplinID, $athleteID, $year);
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
}

