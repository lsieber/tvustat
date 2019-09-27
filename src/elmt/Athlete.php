<?php
namespace tvustat;

class Athlete extends DBTableEntry
{

    /**
     *
     * @var string
     */
    protected $firstName;

    /**
     *
     * @var string
     */
    protected $lastName;

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

    public function __construct(string $firstName, string $lastName, \DateTime $date, Gender $gender, TeamType $teamType, int $id = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->name = $this->getFullName();
        $this->date = $date;
        $this->gender = $gender;
        $this->teamType = $teamType;
        if ($id != null)
            $this->setId($id);
    }

    /**
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->firstName . " " . $this->lastName;
    }

    /**
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
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
     * @return Category
     */
    function getCategory($year)
    {
        return null; // FIXME
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
}

