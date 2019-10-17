<?php
namespace tvustat;

use config\Top;

class DisziplinBestListRaw
{

    /**
     *
     * @var array[Performance]
     */
    protected $performances = array();

    /**
     *
     * @var Disziplin
     */
    protected $disziplin;

    protected function __construct(Disziplin $disziplin)
    {
        $this->disziplin = $disziplin;
    }

    // *********************
    // ADD AND REMOVE ELEMENTS
    // *********************
    /**
     *
     * @param Performance $performance
     */
    public function addPerformance(Performance $performance)
    {
        if ($performance->getDisziplin()->getId() == $this->disziplin->getId()) {
            // Only add if the performnce ID does not exist. This keeps the old version.
            if (! array_key_exists($performance->getId(), $this->performances)) {
                $this->performances[$performance->getId()] = $performance;
            }
        } else {
            echo "we could not add A Performance as it did not have the same ID as the Disziplin Best List. just skiped this performance";
        }
    }

    /**
     *
     * @param int $performanceId
     */
    public function removePerformanceById(int $performanceId)
    {
        if (array_key_exists($performanceId, $this->performances)) {
            unset($this->performances[$performanceId]);
        } else {
            echo "Performance With ID = " . $performanceId . " was not in the List and could not be removed";
        }
    }

    /**
     *
     * @param DisziplinBestListRaw $disziplinBestList
     * @return boolean
     */
    public function mergeDisziplinBestList(DisziplinBestListRaw $disziplinBestList)
    {
        if ($this->getDisziplin()->getId() == $disziplinBestList->getDisziplin()->getId()) {
            foreach ($disziplinBestList->performances as $performance) {
                $this->addPerformance($performance);
            }
            return TRUE;
        }
        return FALSE;
    }

    // *********************
    // GETTERS
    // *********************

    /**
     *
     * @param int $top
     *            if top is null the complete best list is given else the first $top elements of the list
     * @return array|\tvustat\Performance[]
     */
    public function getTopList(int $top = NULL)
    {
//         echo "</br> TOP Parameter  = " . $top . " </br>";
        if ($top != NULL and $top > 0) {
            return array_slice($this->performances, 0, $top);
        }
        return $this->performances;
    }

    /**
     *
     * @return Disziplin
     */
    public function getDisziplin()
    {
        return $this->disziplin;
    }
}