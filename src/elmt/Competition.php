<?php
namespace tvustat;

class Competition extends DBTableEntry
{

    // Mandatory Fields
    /**
     *
     * @var CompetitionName
     */
    private $name;

    /**
     *
     * @var CompetitionLocation
     */
    private $location;

    /**
     *
     * @var \DateTime
     */
    private $date;

    public function __construct(CompetitionName $name, CompetitionLocation $location, \DateTime $date, string $id = NULL)
    {
        $this->name = $name;
        $this->date = $date;
        $this->location = $location;
        if ($id != NULL)
            $this->setId($id);
    }

    /**
     *
     * @return string
     */
    public function getYear()
    {
        return DateFormatUtils::formatDateaAsYear($this->date);
    }

    /**
     *
     * @return string
     */
    public function getFormatedDateForBL()
    {
        return DateFormatUtils::formatDateForBL($this->date);
    }

    /**
     *
     * @return string
     */
    public function getFormatedDateForDB()
    {
        return DateFormatUtils::formatDateForDB($this->date);
    }

    // *********************
    // GETTERS AND SETTERS
    // *********************
    /**
     *
     * @return CompetitionName
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return CompetitionLocation
     */
    public function getLocation()
    {
        return $this->location;
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
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     *
     * @param Competition $otherCompetition
     * @return boolean
     */
    public function equals(Competition $otherCompetition)
    {
        if ($otherCompetition == NULL)
            return FALSE;

        return $otherCompetition->getName()->getId() == $this->getName()->getId() && $otherCompetition->getId() == $this->getId() && $otherCompetition->getDate() == $this->getDate() && $otherCompetition->getLocation()->getId() == $this->getLocation()->getId();
    }
}

