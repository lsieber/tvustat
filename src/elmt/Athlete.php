<?php
namespace tvustat;

class Athlete extends DBTableEntry
{

    /**
     *
     * @var string
     */
    protected $fullName;

    /**
     *
     * @var Gender
     */
    protected $gender;

    /**
     *
     * @var TeamType
     */
    protected $teamType;

    /**
     *
     * @var \DateTime
     */
    protected $date;

    /**
     */
    protected $teamCategory;

    /**
     * 
     * @var int
     */
    protected $licenseNumber;
    
    /**
     *
     * @var string
     */
    protected $saId;
    
    
    public function __construct(string $fullName, \DateTime $date = NULL, Gender $gender, TeamType $teamType, Category $teamCategory = NULL, int $id = null, int $licenseNumber = null, string $saId = null)
    {
        $this->fullName = $fullName;
        $this->name = $this->getFullName();
        $this->gender = $gender;
        $this->teamType = $teamType;
        if ($teamType->getId() == 1) {
            $this->date = $date;
            $this->teamCategory = NULL;
            assert(! is_null($date), "For Team Type = 1 the Date has to be non null");
        } else {
            assert($gender->getId() == $teamCategory->getGender()->getId(), "The team Category Gender has to match the gender given in the constructor");
            assert(! is_null($teamCategory), "For Team Type = 2 the teamCategory has to be non null");
            $this->teamCategory = $teamCategory;
            $this->date = NULL;
        }
        if ($id != null)
            $this->setId($id);
        $this->licenseNumber = $licenseNumber;
        $this->saId = $saId;
    }

    /**
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     *
     * @return Gender
     */
    public final function getGender()
    {
        return $this->gender;
    }

    /**
     *
     * @return \tvustat\TeamType
     */
    public function getTeamType()
    {
        return $this->teamType;
    }

    /**
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     *
     * @return Category
     */
    public function getTeamCategory()
    {
        return $this->teamCategory;
    }

    /**
     *
     * @return string
     */
    public function getDateAsString()
    {
        return DateFormatUtils::formatDateForBL($this->date);
    }

    /**
     *
     * @return string
     */
    public function getDateForDB()
    {
        return DateFormatUtils::formatDateForDB($this->date);
    }
    
    /**
     * @return number
     */
    public function getLicenseNumber()
    {
        return $this->licenseNumber;
    }
    
    /**
     * @return string
     */
    public function getSaId()
    {
        return $this->saId;
    }
    
    
    
    
}

