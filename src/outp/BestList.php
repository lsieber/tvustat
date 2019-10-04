<?php
namespace tvustat;

use config\DefaultSettings;

class BestList
{

    // mandatory varibales
    private $bestList = array();

    // Formating Variables
    private $formatTime;

    private $top;

    // additional variables
    private $html;

    private $txt;

    private function __construct( //
    string $dateFormat = DefaultSettings::DATEFORMAT, //
    bool $topAllValues = DefaultSettings::TOPALLVAALUES) //
    {
        $this->dateFormat = $dateFormat;
        $this->top = $topAllValues;
    }

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

    public function keepBestPerformancePerPerson()
    {
        foreach ($this->bestList as $disBestList) {
            $this->keepBestPerformance($disBestList);
        }
    }
    
    private function keepBestPerformance(DisziplinBestList $disBestList) {
        if ($disBestList->getDisziplin()->getTeamType()->getId() == 1) { // TODO define at other place
            $disBestList->keepBestPerformancePerPerson();
        }
    }

    // *************
    // OUTPUT
    // *************
    public function createHTMLBestList()
    {
        $this->html = "<table>";
        $this->html .= HtmlGenerator::htmlTableBestListHeader();
        $this->html .= "<tbody>";
        foreach ($this->bestList as $disBestList) {
            $this->html .= HtmlGenerator::htmlOfDisziplinBestListForTableBestList($disBestList);
        }
        $this->html .= "</tbody></table>";
        return $this->html;
    }

    public function createHTMLRecord()
    {
        $this->html = "<table>";
        $this->html .= HtmlGenerator::htmlTableRecordtHeader();
        $this->html .= "<tbody>";
        foreach ($this->bestList as $disBestList) {
            $this->html .= HtmlGenerator::htmlOfDisziplinBestListForRecordTable($disBestList);
        }
        $this->html .= "</tbody></table>";
        return $this->html;
    }

    public function createTXT($path, $title)
    {
        $myfile = fopen($path, "w") or die("Unable to open file!");
        fwrite($myfile, "\r\n");
        fwrite($myfile, $title . "\t\t" . "Erstellt am: " . date("d.m.Y") . "\r\n");

        foreach ($this->bestList as $DisziplinBestList) {
            fwrite($myfile, "\n");
            fwrite($myfile, utf8_decode($DisziplinBestList->getDisziplin()->getName() . "\n"));
            foreach ($DisziplinBestList->getTopList() as $performance) {
                // Change Information in Best List .TXT HERE!!!
                $a = implode("\t ", array(
                    $performance->getPerson()->getFullName(),
                    $performance->getFormatedPerformance()
                ));
                fwrite($myfile, utf8_decode($a . "\n"));
            }
        }
        fclose($myfile);
        return "Success: Text File Created!";
    }

    public function printBestLIst()
    {
        foreach ($this->bestList as $disBestList) {
            $disBestList->printBestList();
            echo "<br>";
        }
    }

    // ****************
    // GETTERS AND SETTERS
    // ****************
    /**
     *
     * @return bool
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     *
     * @param bool $top
     */
    public function setTop($top)
    {
        $this->top = $top;
        foreach ($this->bestList as $disBestList) {
            $disBestList->setTop($top);
        }
    }
}

