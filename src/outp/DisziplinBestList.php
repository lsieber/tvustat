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

    private function __construct(Disziplin $disziplin, bool $topAllValues = DefaultSettings::TOPALLVAALUES)
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

    public function keepBestPerformancePerPerson(string $manualTiming)
    {
        $bestElectricalPerPerson = array();
        $bestManualPerPerson = array();
        $performancesToRemove = array();
        $perfId2Key = array();
        foreach ($this->performances as $key => $performance) {
            $perfId2Key[$performance->getId()] = $key;

            if ($performance->getManualTiming()) {
                if (array_key_exists($performance->getAthlete()->getId(), $bestManualPerPerson)) {
                    $bestPerformance = $bestManualPerPerson[$performance->getAthlete()->getId()];
                    if (DisziplinBestList::cmp($bestPerformance, $performance) > 0) {
                        array_push($performancesToRemove, $perfId2Key[$bestPerformance->getId()]);
                        $bestManualPerPerson[$performance->getAthlete()->getId()] = $performance;
                    } else {
                        array_push($performancesToRemove, $key);
                    }
                } else {
                    $bestManualPerPerson[$performance->getAthlete()->getId()] = $performance;
                }
            } else {
                if (array_key_exists($performance->getAthlete()->getId(), $bestElectricalPerPerson)) {
                    $bestPerformance = $bestElectricalPerPerson[$performance->getAthlete()->getId()];
                    if (DisziplinBestList::cmp($bestPerformance, $performance) > 0) {
                        array_push($performancesToRemove, $perfId2Key[$bestPerformance->getId()]);
                        $bestElectricalPerPerson[$performance->getAthlete()->getId()] = $performance;
                    } else {
                        array_push($performancesToRemove, $key);
                    }
                } else {
                    $bestElectricalPerPerson[$performance->getAthlete()->getId()] = $performance;
                }
            }
        }

        switch ($manualTiming) {
            case "E":
                foreach ($bestManualPerPerson as $bestPerformance) {
                    array_push($performancesToRemove, $perfId2Key[$bestPerformance->getId()]);
                }
                break;
            case "EORH":
                foreach ($bestManualPerPerson as $m) {
                    if (array_key_exists($m->getAthlete()->getId(), $bestElectricalPerPerson)) {
                        $e = $bestElectricalPerPerson[$m->getAthlete()->getId()];
                        if (DisziplinBestList::cmp($m, $e) > 0) {
                            array_push($performancesToRemove, $perfId2Key[$m->getId()]);
                        } else {
                            array_push($performancesToRemove, $perfId2Key[$e->getId()]);
                        }
                    }
                }
                break;
            case "H":
                foreach ($bestElectricalPerPerson as $bestPerformance) {
                    array_push($performancesToRemove, $perfId2Key[$bestPerformance->getId()]);
                }
                break;
            case "EANDH":
            default:
                break;
        }

        foreach ($performancesToRemove as $performanceToRemove) {
            $this->removePerformanceById($performanceToRemove);
        }
    }

    public function keepBestAthleteAndYear(string $manualTiming)
    {
        $bestElectricalPerPerson = array();
        $bestManualPerPerson = array();
        $performancesToRemove = array();
        $perfId2Key = array();

        foreach ($this->performances as $key => $performance) {
            $perfId2Key[$performance->getId()] = $key;
            // Adding the year to the athlete id distinuishes the results of the different years.
            $athYearId = strval($performance->getAthlete()->getId()) . DateFormatUtils::formatDateaAsYear($performance->getCompetition()->getDate());
            if ($performance->getManualTiming()) {
                if (array_key_exists($athYearId, $bestManualPerPerson)) {
                    $bestPerformance = $bestManualPerPerson[$athYearId];
                    // If there exists no other result which is as good as the current one and in the same year
                    if (DisziplinBestList::cmp($bestPerformance, $performance) > 0) {
                        array_push($performancesToRemove, $perfId2Key[$bestPerformance->getId()]);
                        $bestManualPerPerson[$athYearId] = $performance;
                        // If there exists already a better result for that athlete in this year
                    } else {
                        array_push($performancesToRemove, $key);
                    }
                    // If there exists no result for this athlete in this year
                } else {
                    $bestManualPerPerson[$athYearId] = $performance;
                }
            } else {
                if (array_key_exists($athYearId, $bestElectricalPerPerson)) {
                    $bestPerformance = $bestElectricalPerPerson[$athYearId];
                    if (DisziplinBestList::cmp($bestPerformance, $performance) > 0) {
                        array_push($performancesToRemove, $perfId2Key[$bestPerformance->getId()]);
                        $bestElectricalPerPerson[$athYearId] = $performance;
                    } else {
                        array_push($performancesToRemove, $key);
                    }
                } else {
                    $bestElectricalPerPerson[$athYearId] = $performance;
                }
            }
        }
        switch ($manualTiming) {
            case "E":
                foreach ($bestManualPerPerson as $bestPerformance) {
                    array_push($performancesToRemove, $perfId2Key[$bestPerformance->getId()]);
                }
                break;
            case "EORH":
                foreach ($bestManualPerPerson as $key => $m) {
                    if (array_key_exists($key, $bestElectricalPerPerson)) {
                        $e = $bestElectricalPerPerson[$key];
                        if (DisziplinBestList::cmp($m, $e) > 0) {
                            array_push($performancesToRemove, $perfId2Key[$m->getId()]);
                        } else {
                            array_push($performancesToRemove, $perfId2Key[$e->getId()]);
                        }
                    }
                }
                break;
            case "H":
                foreach ($bestElectricalPerPerson as $bestPerformance) {
                    array_push($performancesToRemove, $perfId2Key[$bestPerformance->getId()]);
                }
                break;
            case "EANDH":
            default:
                break;
        }

        // remove all the supperficial results
        foreach ($performancesToRemove as $performanceToRemove) {
            $this->removePerformanceById($performanceToRemove);
        }
    }
    
    public function keepOnlyElectrical() {
        foreach ($this->performances as $key => $performance) {
            if ($performance->getManualTiming()) {
                $this->removePerformanceById($key);
            }
        }
    }
    
    public function keepOnlyManual() {
        foreach ($this->performances as $key => $performance) {
            if (!$performance->getManualTiming()) {
                $this->removePerformanceById($key);
            }
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


