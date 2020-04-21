<?php
namespace tvustat;

use config\dbPerformance;

class AthleteBestList
{

    // Internal Variables
    private $sql;

    private $db;

    private $bestList;

    private $title;

    private $athletes;

    private $top;

    public function __construct(array $athleteIds, DBMaintainer $db, int $top = 30)
    {
        $this->db = $db;
        $this->top = $top;
        $this->sql = SpecialOutputSQL::createAthlete($athleteIds);
        // echo $this->sql;
        $this->athletes = $db->getAthletes($athleteIds);
        $this->title = new BestListTitleFromString("Resultate für: " . $this->getAthleteNames());
        $this->bestList = BestList::empty();
    }
    
    private function getAthleteNames(){
        $names = array();
        foreach ($this->athletes as $athlete) {
            array_push($names, $athlete->getFullName()." (".DateFormatUtils::formatDateForBL($athlete->getDate()).")");
        }
        return implode(", ", $names);
    }

    public function callDB()
    {
        $array_result = $this->db->getConn()->executeSqlToArray($this->sql);
        // Fill into Best List
        foreach ($array_result as $entry) {
            $performance = dbPerformance::array2Elmt($entry, $this->db->getConn());
            $this->bestList->addPerformance($performance);
        }
    }

    public function formatBestList(string $keep, string $manualTiming)
    {
        $this->bestList->sortPerformances();
        $this->bestList->sortDisziplinOrder();
        $teamType = array();
        foreach ($this->athletes as $athlete){
            if (!in_array($athlete->getTeamType()->getId(), $teamType)){
                array_push($teamType, $athlete->getTeamType()->getId());
            }
        }
        if ($keep == "ATHLETE") {
            $this->bestList->keepBestPerformancePerPerson($teamType, $manualTiming);
        } elseif ($keep == "YEARATHLETE") {
            $this->bestList->keepBestPerAthleteAndYear($teamType, $manualTiming);
        } else {
            assert($keep == "ALL");
        }
    }

    public function printTable(bool $withName)
    {
        $categoryUtils = new CategoryUtils($this->db->getConn());
        $columnDefCatDetail = $withName ? new ColumnDefinitionCatDetail($categoryUtils) : new ColumnDefinitionCatDetailNoName($categoryUtils);
        $columnDefCat = $withName ? new ColumnDefinitionCategory($categoryUtils) : new ColumnDefinitionCategoryNoName($categoryUtils);
        $htmlGenerator = new HtmlGeneratorDisziplinIndiv($columnDefCatDetail, $columnDefCat, $this->title);
        $html = $htmlGenerator->createOutput($this->bestList, $this->top);
        echo $html->getHtml();
    }

    /**
     *
     * @return BestListTitle
     */
    public function getTitle()
    {
        return $this->title;
    }
}

