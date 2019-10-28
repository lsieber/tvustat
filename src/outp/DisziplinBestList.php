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

    public function removeDublicatesFromTVUBuch()
    {
        $removablekeys = array();
        foreach ($this->performances as $key => $performance) {
            if (SourceUtils::isFromTVUBuch($performance)) {
                // Upwards search
                $index = $key;
                while ($this->performances[$index]->getPerformance() == $performance->getPerformance()) {
                    if ($this->performances[$index]->getAthlete()->getId() == $performance->getAthlete()->getId()) {
                        $removablekeys[$key] = true;
                    }
                    $index ++;
                }
                $index = $key;
                while ($this->performances[$index]->getPerformance() == $performance->getPerformance()) {
                    if ($this->performances[$index]->getAthlete()->getId() == $performance->getAthlete()->getId()) {
                        $removablekeys[$key] = true;
                    }
                    $index --;
                }
            }
        }

        foreach ($removablekeys as $key => $performance) {
            $this->removePerformanceById($key);
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

    public function keepBestAthleteAndYear()
    {
        $bestPerfPerPerson = array();
        $performancesToRemove = array();
        $perfId2Key = array();

        foreach ($this->performances as $key => $performance) {
            $perfId2Key[$performance->getId()] = $key;
            // Adding the year to the athlete id distinuishes the results of the different years.
            $athYearId = strval($performance->getAthlete()->getId()) . DateFormatUtils::formatDateaAsYear($performance->getCompetition()->getDate());
            if (array_key_exists($athYearId, $bestPerfPerPerson)) {
                $bestPerformance = $bestPerfPerPerson[$athYearId];
                // If there exists no other result which is as good as the current one and in the same year
                if (DisziplinBestList::cmp($bestPerformance, $performance) > 0) {
                    array_push($performancesToRemove, $perfId2Key[$bestPerformance->getId()]);
                    $bestPerfPerPerson[$athYearId] = $performance;
                    // If there exists already a better result for that athlete in this year
                } else {
                    array_push($performancesToRemove, $key);
                }
                // If there exists no result for this athlete in this year
            } else {
                $bestPerfPerPerson[$athYearId] = $performance;
            }
        }
        // remove all the supperficial results
        foreach ($performancesToRemove as $performanceToRemove) {
            $this->removePerformanceById($performanceToRemove);
        }
    }

    // ********************
    // OUTPUT
    // ********************

    /*
     * CONSOLE PRINT OUT
     */
    public function printBestList($top = NULL)
    {
        echo "<br>";
        echo $this->disziplin->getName();
        foreach ($this->getTopList($top) as $performance) {
            echo "<br>";
            $performance->print();
        }
    }
}


