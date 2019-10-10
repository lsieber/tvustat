<?php
namespace tvustat;

use config\DefaultSettings;

class DisziplinBestList extends DisziplinBestListRaw
{

    /**
     *
     * @var bool
     */
    private $top;

    private function __construct( //
    Disziplin $disziplin, //
    bool $topAllValues = DefaultSettings::TOPALLVAALUES) //
    {
        parent::__construct($disziplin);
        $this->top = $topAllValues;
    }

    // *********************
    // CONSTRUCTOR Functions
    // *********************
    public static function fromDisziplin(Disziplin $disziplin)
    {
        $disziplinBestList = new self($disziplin);
        return $disziplinBestList;
    }

    public static function fromPerformances(Performance ...$performances)
    {
        $disziplin = $performances[0]->getDisziplin();
        $disziplinBestList = new self($disziplin);

        foreach ($performances as $performance) {
            $disziplinBestList->addPerformance($performance);
        }
        return $disziplinBestList;
    }

    // *********************
    // FORMAT BEST LIST
    // *********************
    public function sortPerformances()
    {
        // var_dump($this->performances);
        usort($this->performances, array(
            "tvustat\DisziplinBestList",
            "cmp"
        ));
    }

    private static function cmp(Performance $a, Performance $b)
    {
        if ($a->getPerformance() == $b->getPerformance()) {
            if ($a->getDisziplin()
                ->getSorting()
                ->sortASC()) {
                return ($a->getPerformance() < $b->getPerformance()) ? - 1 : 1;
            } else {
                return ($a->getPerformance() > $b->getPerformance()) ? - 1 : 1;
            }
            return 0;
        }
        assert($a->getDisziplin()->getId() == $b->getDisziplin()->getId()); // Should never appear as we check this when a performance is inserted
        if ($a->getDisziplin()
            ->getSorting()
            ->sortASC()) {
            return ($a->getPerformance() < $b->getPerformance()) ? - 1 : 1;
        } else {
            return ($a->getPerformance() > $b->getPerformance()) ? - 1 : 1;
        }
    }

    public function keepBestPerformancePerPerson()
    {
        $bestPerfPerPerson = array();
        $performancesToRemove = array();
        // TODO might create a field from that;
        $perfId2Key = array();
        foreach ($this->performances as $key => $performance) {
            $perfId2Key[$performance->getId()] = $key;
            if (array_key_exists($performance->getAthlete()->getId(), $bestPerfPerPerson)) {
                $bestPerformance = $bestPerfPerPerson[$performance->getAthlete()->getId()];
                if (DisziplinBestList::cmp($bestPerformance, $performance) > 0) {
                    array_push($performancesToRemove, $perfId2Key[$bestPerformance->getId()]);
                    $bestPerfPerPerson[$performance->getAthlete()->getId()] = $performance;
                } else {
                    array_push($performancesToRemove, $key);
                }
            } else {
                $bestPerfPerPerson[$performance->getAthlete()->getId()] = $performance;
            }
        }
        foreach ($performancesToRemove as $performanceToRemove) {
            $this->removePerformanceById($performanceToRemove);
        }
    }

    public function getTopList()
    {
        if ($this->top != DefaultSettings::TOPALLVAALUES and $this->top >= 0) {
            return array_slice($this->performances, 0, $this->top);
        }
        return $this->performances;
    }

    // ********************
    // OUTPUT
    // ********************

    /*
     * CONSOLE PRINT OUT
     */
    public function printBestList()
    {
        echo "<br>";
        echo $this->disziplin->getName();
        foreach ($this->getTopList() as $performance) {
            echo "<br>";
            $performance->print();
        }
    }

    // ********************
    // GETTERS And SETTERS
    // ********************

    /**
     *
     * @return boolean
     */
    public function isTop()
    {
        return $this->top;
    }

    /**
     *
     * @param boolean $top
     */
    public function setTop($top)
    {
        $this->top = $top;
    }
}


