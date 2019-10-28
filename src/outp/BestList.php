<?php
namespace tvustat;

class BestList
{

    // mandatory varibales
    /**
     *
     * @var array[DisziplinBestList]
     */
    private $bestList = array();

    public static function empty()
    {
        return new self();
    }

    public function addDisziplinBestList(DisziplinBestList $disziplinBestList)
    {
        $disziplinId = $disziplinBestList->getDisziplin()->getId();
        if (array_key_exists($disziplinId, $this->bestList)) {
            $this->bestList[$disziplinId]->mergeDisziplinBestList($disziplinBestList);
        } else {
            $this->bestList[$disziplinId] = $disziplinBestList;
        }
    }

    public function addPerformances(Performance ...$performances)
    {
        foreach ($performances as $performance) {
            $this->addPerformance($performance);
        }
    }

    public function addPerformance(Performance $performance)
    {
        $disziplinId = $performance->getDisziplin()->getId();
        if (array_key_exists($disziplinId, $this->bestList)) {
            $this->bestList[$disziplinId]->addPerformance($performance);
        } else {
            $newDisziplinBestList = DisziplinBestList::fromPerformances($performance);
            $this->addDisziplinBestList($newDisziplinBestList);
        }
    }

    public function removePerformanceById(int $performanceId)
    {
        foreach ($this->bestList as $disBestList) {
            $disBestList->removePerformanceById($performanceId);
        }
    }

    public function sortPerformances()
    {
        foreach ($this->bestList as $disBestList) {
            $disBestList->sortPerformances();
        }
    }

    public function removeDublicatesFromTVUBuch()
    {
        foreach ($this->bestList as $disBestList) {
            $disBestList->removeDublicatesFromTVUBuch();
        }
    }

    public function sortDisziplinOrder()
    {
        usort($this->bestList, array(
            "tvustat\BestList",
            "cmp"
        ));
    }

    private static function cmp(DisziplinBestList $a, DisziplinBestList $b)
    {
        if ($a->getDisziplin()->getOrderNumber() == $b->getDisziplin()->getOrderNumber()) {
            return strcmp($a->getDisziplin()->getName(), $b->getDisziplin()->getName());
        }
        return ($a->getDisziplin()->getOrderNumber() < $b->getDisziplin()->getOrderNumber()) ? - 1 : 1;
    }

    public function keepBestPerformancePerPerson(array $teamTypes)
    {
        if (sizeof($teamTypes) > 0) {
            foreach ($this->bestList as $disBestList) {
                $this->keepBestPerformance($disBestList, $teamTypes);
            }
        }
    }

    private function keepBestPerformance(DisziplinBestList $disBestList, array $teamTypes)
    {
        if (in_array($disBestList->getDisziplin()
            ->getTeamType()
            ->getId(), $teamTypes)) {
            $disBestList->keepBestPerformancePerPerson();
        }
    }

    public function keepBestPerAthleteAndYear(array $teamTypes)
    {
        if (sizeof($teamTypes) > 0) {
            foreach ($this->bestList as $disBestList) {
                $this->keepBestAthleteAndYear($disBestList, $teamTypes);
            }
        }
    }

    private function keepBestAthleteAndYear(DisziplinBestList $disBestList, array $teamTypes)
    {
        if (in_array($disBestList->getDisziplin()
            ->getTeamType()
            ->getId(), $teamTypes)) {
            $disBestList->keepBestAthleteAndYear();
        }
    }

    // ****************
    // GETTERS AND SETTERS
    // ****************

    /**
     *
     * @return array DisziplinBestList
     */
    public function getDisziplinBestLists()
    {
        return $this->bestList;
    }
}

