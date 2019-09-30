<?php
namespace tvustat;

class Performance extends DBTableEntry
{

    // Mandatory Variables
    /**
     *
     * @var float
     */
    private $performance;

    /**
     *
     * @var float
     */
    private $wind;

    /**
     *
     * @var string
     */
    private $ranking;

    /**
     *
     * @var Disziplin
     */
    private $disziplin;

    /**
     *
     * @var Person
     */
    private $person;

    /**
     *
     * @var Competition
     */
    private $competition;

    public function __construct(float $performance, float $wind = NULL, string $ranking = NULL, Disziplin $disziplin, Person $person, Competition $competition, int $id = NULL)
    {
        $this->performance = $performance;
        $this->wind = $wind;
        $this->ranking = $ranking;
        $this->disziplin = $disziplin;
        $this->person = $person;
        $this->competition = $competition;
        if ($id != NULL)
            $this->setId($id);
    }

    public function print()
    {
        echo $this->disziplin->getName() . ": " . $this->getFormatedPerformance() . " \t" . $this->person->getFullNameAndBornDate() . " \t" . $this->competition->getPlace() . " \t" . $this->getFormatedDate();
    }

    public function getFormatedPerformance()
    {
        $formatedPerformance = $this->performance;

        if ($this->disziplin->isDecimal()) {
            $formatedPerformance = TimeUtils::twoDigitsEnd($formatedPerformance);
        }

        if ($this->disziplin->isTime()) {
            $formatedPerformance = TimeUtils::second2time($formatedPerformance);
        }

        return $formatedPerformance;
    }

    // ********************
    // GETTERS And SETTERS
    // ********************

    /**
     *
     * @return mixed
     */
    public function getPerformance()
    {
        return $this->performance;
    }

    /**
     *
     * @return Disziplin
     */
    public function getDisziplin()
    {
        return $this->disziplin;
    }

    /**
     *
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     *
     * @return Competition
     */
    public function getCompetition()
    {
        return $this->competition;
    }
}

