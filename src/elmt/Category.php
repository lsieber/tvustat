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
     */
    public function getName()
    {
        return $this->name;
    }
}

