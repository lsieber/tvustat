<?php
namespace tvustat;

class Category extends DBTableEntry
{

    private $name;

    private $oldname;

    private $ageCategory;

    private $gender;

    public function __construct(AgeCategory $ageCategory, Gender $gender, string $name, string $oldname, int $id = NULL)
    {
        $this->ageCategory = $ageCategory;
        $this->gender = $gender;
        $this->name = ($name != null) ? $name : $ageCategory->getName() . " " . $gender->getName();
        $this->oldname = $oldname;
        $this->setId($id);
    }

    public function equals(Category $otherCategory)
    {
        return ( //
        $this->getAgeCategory()->getName() == $otherCategory->getAgeCategory()->getName() && //
        $this->getGender()->getName() == $otherCategory->getGender()->getName() //
        );
    }

    // public function createHTMLInputfield($name = "category", $type = "radio", $onclick = NULL, $checked = NULL)
    // {
    // $onclickString = ($onclick != NULL) ? "onclick='" . implode(";", $onclick) . "' " : "";
    // $checked = ($checked != NULL) ? " checked='yes'" : "";
    // return "<input type='" . $type . "' name='" . $name . "[]' id='" . $this->name . "' value='" . $this->valueHTML . "'" . $checked . $onclickString . " /> " . $this->name . " <br/>";
    // }

    // public function getSQLforPerson($year)
    // {
    // $minJg = $year - $this->ageCategory->getMinAge();
    // if ($this->ageCategory->getName()== AgeCategories::ACT) {
    // $minJg = $year - AgeCategories::ADULTAGE;
    // }
    // $maxJg = $year - $this->ageCategory->getMaxAge();
    // $genderLIst = Genders::getGenderListForSQLInStatement($this->getGender());
    // $aktiv = $year - AgeCategories::ADULTAGE;
    // return "SELECT * FROM mitglied WHERE Jg >= " . $maxJg . " AND Jg <= " . $minJg . " AND Geschlecht IN (" . $genderLIst . ") AND aktiv + Jg >= " . $aktiv . " AND TeamKategorie IS NULL ORDER BY Vorname, Name";
    // }

    // public function getSQLforTeam($year)
    // {
    // $genders = new Genders();
    // $arrayGenders = $genders->getTeamGenderList($this->getGender());
    // $arrayNumericalGenders = array();
    // foreach ($arrayGenders as $gender) {
    // array_push($arrayNumericalGenders, $gender->getNumericalValue());
    // }
    // return "SELECT * FROM mitglied WHERE Jg = " . $year . " AND TeamKategorie = '" . $this->ageCategory->getName() . "' AND Geschlecht IN (" . implode(",", $arrayNumericalGenders) . ")";
    // }

    // ***********
    // GETTERS
    // ***********

    /**
     *
     * @return AgeCategory
     */
    public function getAgeCategory()
    {
        return $this->ageCategory;
    }

    /**
     *
     * @return Gender
     */
    public function getGender()
    {
        return $this->gender;
    }
    
    /**
     * 
     */
    public function getName() {
        return $this->name;
    }
}

