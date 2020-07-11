<?php
namespace tvustat;

use config\dbDisziplinTypes;
use config\dbGenders;
use config\dbSorting;
use config\dbTeamTypes;
use config\dbCategory;
use config\dbAgeCategory;
use config\dbPerformanceSource;
use config\dbPointSchemeNames;
use config\dbPointSchemes;
use config\dbPointParameters;

class ConnectionPreloaded
{

    /**
     *
     * @var array
     */
    protected $genders = array();

    /**
     *
     * @var array
     */
    protected $teamTypes = array();

    /**
     *
     * @var array
     */
    protected $disziplinTypes = array();

    /**
     *
     * @var array
     */
    protected $sortings = array();

    /**
     *
     * @var array
     */
    protected $categories = array();

    /**
     *
     * @var array
     */
    protected $ageCategories = array();

    /**
     *
     * @var array
     */
    protected $sources = array();

    /**
     *
     * @var array
     */
    protected $pointSchemeNames = array();

    /**
     *
     * @var array
     */
    protected $pointSchemes = array();

    /**
     *
     * @var array
     */
    protected $pointParameter = array();

    /**
     *
     * @var Connection
     */
    private $conn;

    public function __construct()
    {
        $this->conn = new Connection();
        $this->loadGenders();
        $this->loadTeamTypes();
        $this->loadDisziplinTypes();
        $this->loadSorting();
        $this->loadAgeCategories();
        $this->loadCategories();
        $this->loadSources();
        $this->loadPointSchemeNames();
        $this->loadPointSchemes();
        $this->loadPointParameter();
    }

    private function loadGenders()
    {
        $sql = "SELECT * From " . dbGenders::DBNAME;
        $array = $this->conn->executeSqlToArray($sql);
        foreach ($array as $value) {
            $this->genders[$value[dbGenders::ID]] = new Gender($value[dbGenders::TYPE], $value[dbGenders::TYPE], $value[dbGenders::ID]);
        }
    }

    private function loadTeamTypes()
    {
        $sql = "SELECT * From " . dbTeamTypes::DBNAME;
        $array = $this->conn->executeSqlToArray($sql);
        foreach ($array as $value) {
            $this->teamTypes[$value[dbTeamTypes::ID]] = new TeamType($value[dbTeamTypes::TYPE], $value[dbTeamTypes::ID]);
        }
    }

    private function loadDisziplinTypes()
    {
        $sql = "SELECT * From " . dbDisziplinTypes::DBNAME;
        $array = $this->conn->executeSqlToArray($sql);
        foreach ($array as $value) {
            $this->disziplinTypes[$value[dbDisziplinTypes::ID]] = new DisziplinType($value[dbDisziplinTypes::TYPE], $value[dbDisziplinTypes::ID]);
        }
    }

    private function loadSorting()
    {
        $sql = "SELECT * From " . dbSorting::DBNAME;
        $array = $this->conn->executeSqlToArray($sql);
        foreach ($array as $value) {
            $this->sortings[$value[dbSorting::ID]] = new Sorting($value[dbSorting::DIRECTION], $value[dbSorting::SQL], $value[dbSorting::ID]);
        }
    }

    private function loadAgeCategories()
    {
        $sql = "SELECT * From " . dbAgeCategory::DBNAME;
        $array = $this->conn->executeSqlToArray($sql);
        foreach ($array as $v) {
            $this->ageCategories[$v[dbAgeCategory::ID]] = new AgeCategory($v[dbAgeCategory::NAME], $v[dbAgeCategory::MINAGE], $v[dbAgeCategory::MAXAGE], $v[dbAgeCategory::ID]);
        }
    }

    private function loadCategories()
    {
        $sql = "SELECT * From " . dbCategory::DBNAME;
        $array = $this->conn->executeSqlToArray($sql);
        foreach ($array as $v) {
            $ageCategory = $this->getAgeCategory($v[dbCategory::AGECATEGORYID]);
            $gender = $this->getGender($v[dbCategory::GENDERID]);
            $this->categories[$v[dbCategory::ID]] = new Category($ageCategory, $gender, $v[dbCategory::NAME], $v[dbCategory::NAMEOLD], $v[dbCategory::ID]);
        }
    }

    private function loadSources()
    {
        $sql = "SELECT * From " . dbPerformanceSource::DBNAME;
        $array = $this->conn->executeSqlToArray($sql);
        foreach ($array as $v) {
            $this->sources[$v[dbPerformanceSource::ID]] = dbPerformanceSource::sourceFromAssocArray($v);
        }
    }

    private function loadPointSchemeNames()
    {
        $sql = "SELECT * From " . dbPointSchemeNames::DBNAME;
        $array = $this->conn->executeSqlToArray($sql);
        foreach ($array as $v) {
            $this->pointSchemeNames[$v[dbPointSchemeNames::ID]] = $v;
        }
    }

    private function loadPointSchemes()
    {
        $sql = "SELECT * From " . dbPointSchemes::DBNAME;
        $array = $this->conn->executeSqlToArray($sql);
        foreach ($array as $v) {
            $this->pointSchemes[$v[dbPointSchemes::ID]] = $v;
        }
    }

    private function loadPointParameter()
    {
        $sql = "SELECT * From " . dbPointParameters::DBNAME;
        $array = $this->conn->executeSqlToArray($sql);
        foreach ($array as $v) {
            if (! array_key_exists($v[dbPointParameters::SCHEMEID], $this->pointParameter)) {
                $this->pointParameter[$v[dbPointParameters::SCHEMEID]] = array();
            }
            $this->pointParameter[$v[dbPointParameters::SCHEMEID]][$v[dbPointParameters::DISZIPLINID]] = $v;
        }
    }

    /**
     *
     * @param int $id
     * @return Gender or null if the $id does not exist in the range of possible genders
     */
    public function getGender(int $id)
    {
        return (isset($this->genders[$id])) ? $this->genders[$id] : null;
    }

    /**
     *
     * @param int $id
     * @return TeamType or null if the $id does not exist in the range of possible genders
     */
    public function getTeamType(int $id)
    {
        return (isset($this->teamTypes[$id])) ? $this->teamTypes[$id] : null;
    }

    /**
     *
     * @param int $id
     * @return TeamType or null if the $id does not exist in the range of possible genders
     */
    public function getDisziplinType(int $id)
    {
        return (isset($this->disziplinTypes[$id])) ? $this->disziplinTypes[$id] : null;
    }

    /**
     *
     * @param int $id
     * @return AgeCategory or null if the $id does not exist in the range of possible genders
     */
    public function getAgeCategory(int $id)
    {
        return (isset($this->ageCategories[$id])) ? $this->ageCategories[$id] : null;
    }

    /**
     *
     * @param int $id
     * @return Category or null if the $id does not exist in the range of possible genders
     */
    public function getCategory(int $id)
    {
        return (isset($this->categories[$id])) ? $this->categories[$id] : null;
    }

    /**
     *
     * @param int $id
     * @return Sorting or null if the $id does not exist in the range of possible genders
     */
    public function getSorting(int $id)
    {
        return (isset($this->sortings[$id])) ? $this->sortings[$id] : null;
    }

    /**
     *
     * @param int $id
     * @return PerformanceSource | NULL
     */
    public function getSource(?int $id)
    {
        return (isset($this->sources[$id])) ? $this->sources[$id] : null;
    }

    public function getAllGenders()
    {
        return $this->genders;
    }

    public function getAllTeamTypes()
    {
        return $this->teamTypes;
    }

    public function getAllSortings()
    {
        return $this->sortings;
    }

    public function getAllDisziplinTypes()
    {
        return $this->disziplinTypes;
    }

    public function getAllCategories()
    {
        return $this->categories;
    }

    public function getSources()
    {
        return $this->sources;
    }

    public function getPointSchemeNames()
    {
        return $this->pointSchemeNames;
    }

    public function getPointSchemes()
    {
        return $this->pointSchemes;
    }

    public function getPointParameters()
    {
        return $this->pointParameter;
    }

    /**
     *
     * @param string $sql
     * @return mixed
     */
    public function executeSqlToArray(string $sql)
    {
//         echo "</br> ". $sql . "</br>";
        return $this->conn->executeSqlToArray($sql);
    }

    /**
     *
     * @return \mysqli
     */
    public function getConn()
    {
        return $this->conn->getConn();
    }
    
    /**
     * @return Connection
     */
    public function getConnection(){
        return $this->conn;
    }
}

