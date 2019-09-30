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

    public function __construct(string $fullName, \DateTime $date, Gender $gender, TeamType $teamType, int $id = null)
    {
        $this->fullName = $fullName;
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

