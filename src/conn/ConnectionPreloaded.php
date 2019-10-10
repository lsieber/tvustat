<?php
namespace tvustat;

use config\dbDisziplinTypes;
use config\dbGenders;
use config\dbSorting;
use config\dbTeamTypes;
use config\dbCategory;
use config\dbAgeCategory;

class ConnectionPreloaded extends Connection
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

    public function __construct()
    {
        parent::__construct();
        $this->loadGenders();
        $this->loadTeamTypes();
        $this->loadDisziplinTypes();
        $this->loadSorting();
        $this->loadAgeCategories();
        $this->loadCategories();
    }

    private function loadGenders()
    {
        $sql = "SELECT * From " . dbGenders::DBNAME;
        $array = $this->executeSqlToArray($sql);
        foreach ($array as $value) {
            $this->genders[$value[dbGenders::ID]] = new Gender($value[dbGenders::TYPE], $value[dbGenders::TYPE], $value[dbGenders::ID]);
        }
    }

    private function loadTeamTypes()
    {
        $sql = "SELECT * From " . dbTeamTypes::DBNAME;
        $array = $this->executeSqlToArray($sql);
        foreach ($array as $value) {
            $this->teamTypes[$value[dbTeamTypes::ID]] = new TeamType($value[dbTeamTypes::TYPE], $value[dbTeamTypes::ID]);
        }
    }

    private function loadDisziplinTypes()
    {
        $sql = "SELECT * From " . dbDisziplinTypes::DBNAME;
        $array = $this->executeSqlToArray($sql);
        foreach ($array as $value) {
            $this->disziplinTypes[$value[dbDisziplinTypes::ID]] = new DisziplinType($value[dbDisziplinTypes::TYPE], $value[dbDisziplinTypes::ID]);
        }
    }

    private function loadSorting()
    {
        $sql = "SELECT * From " . dbSorting::DBNAME;
        $array = $this->executeSqlToArray($sql);
        foreach ($array as $value) {
            $this->sortings[$value[dbSorting::ID]] = new Sorting($value[dbSorting::DIRECTION], $value[dbSorting::SQL], $value[dbSorting::ID]);
        }
    }

    private function loadAgeCategories()
    {
        $sql = "SELECT * From " . dbAgeCategory::DBNAME;
        $array = $this->executeSqlToArray($sql);
        foreach ($array as $v) {
            $this->ageCategories[$v[dbAgeCategory::ID]] = new AgeCategory($v[dbAgeCategory::NAME], $v[dbAgeCategory::MINAGE], $v[dbAgeCategory::MAXAGE], $v[dbAgeCategory::ID]);
        }
    }

    private function loadCategories()
    {
        $sql = "SELECT * From " . dbCategory::DBNAME;
        $array = $this->executeSqlToArray($sql);
        foreach ($array as $v) {
            $ageCategory = $this->getAgeCategory($v[dbCategory::AGECATEGORYID]);
            $gender = $this->getGender($v[dbCategory::GENDERID]);
            $this->categories[$v[dbCategory::ID]] = new Category($ageCategory, $gender, $v[dbCategory::NAME], $v[dbCategory::NAMEOLD], $v[dbCategory::ID]);
        }
    }

    /**
     *
     * @param int $id
     * @return Gender or null if the $id does not exist in the range of possible genders
     */
    public function getGender($id)
    {
        return (isset($this->genders[$id])) ? $this->genders[$id] : null;
    }

    /**
     *
     * @param int $id
     * @return TeamType or null if the $id does not exist in the range of possible genders
     */
    public function getTeamType($id)
    {
        return (isset($this->teamTypes[$id])) ? $this->teamTypes[$id] : null;
    }

    /**
     *
     * @param int $id
     * @return TeamType or null if the $id does not exist in the range of possible genders
     */
    public function getDisziplinType($id)
    {
        return (isset($this->disziplinTypes[$id])) ? $this->disziplinTypes[$id] : null;
    }

    /**
     *
     * @param int $id
     * @return AgeCategory or null if the $id does not exist in the range of possible genders
     */
    public function getAgeCategory($id)
    {
        return (isset($this->ageCategories[$id])) ? $this->ageCategories[$id] : null;
    }

    /**
     *
     * @param int $id
     * @return Category or null if the $id does not exist in the range of possible genders
     */
    public function getCategory($id)
    {
        return (isset($this->categories[$id])) ? $this->categories[$id] : null;
    }

    /**
     *
     * @param int $id
     * @return Sorting or null if the $id does not exist in the range of possible genders
     */
    public function getSorting($id)
    {
        return (isset($this->sortings[$id])) ? $this->sortings[$id] : null;
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
}

