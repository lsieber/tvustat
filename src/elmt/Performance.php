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
     * @var boolean
     */
    private $manualTiming;

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

    /**
     *
     * @var string
     */
    private $detail;

    /**
     *
     * @var PerformanceSource
     */
    private $source;

    public function __construct(Disziplin $disziplin, Athlete $athlete, Competition $competition, float $performance, float $wind = NULL, string $ranking = NULL, bool $manualTiming = FALSE, PerformanceSource $source = NULL, int $id = NULL, string $detail = NULL)
    {
        $this->performance = $performance;
        $this->wind = $wind;
        $this->ranking = $ranking;
        $this->manualTiming = $manualTiming;
        $this->disziplin = $disziplin;
        $this->athlete = $athlete;
        $this->competition = $competition;
        $this->source = $source;
        if ($id != NULL)
            $this->setId($id);
        $this->detail = $detail;
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
        
        if ($this->manualTiming) {
            // TODO no magic constant
            $formatedPerformance = $formatedPerformance . " h";
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

    /**
     *
     * @return float | null
     */
    public function getWind()
    {
        return $this->wind;
    }

    /**
     *
     * @return string
     */
    public function getPlacement()
    {
        return $this->ranking;
    }
    
    /**
     *
     * @return bool
     */
    public function getManualTiming()
    {
        return $this->manualTiming;
    }

    /**
     *
     * @return string | NULL
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     *
     * @return \tvustat\PerformanceSource
     */
    public function getSource()
    {
        return $this->source;
    }
}

