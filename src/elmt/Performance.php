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
     * @var Athlete
     */
    private $athlete;

    /**
     *
     * @var Competition
     */
    private $competition;

    public function __construct(Disziplin $disziplin, Athlete $athlete, Competition $competition, float $performance, float $wind = NULL, string $ranking = NULL, int $id = NULL)
    {
        $this->performance = $performance;
        $this->wind = $wind;
        $this->ranking = $ranking;
        $this->disziplin = $disziplin;
        $this->athlete = $athlete;
        $this->competition = $competition;
        if ($id != NULL)
            $this->setId($id);
    }

    public function print()
    {
        echo $this->disziplin->getName() . ": " . $this->getFormatedPerformance() . " \t" . $this->athlete->getFullNameAndBornDate() . " \t" . $this->competition->getPlace() . " \t" . $this->getFormatedDate();
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
     * @return Athlete
     */
    public function getAthlete()
    {
        return $this->athlete;
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

