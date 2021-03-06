<?php
namespace tvustat;

use config\dbPerformance;

class CompetitionBestList
{

    // Internal Variables
    private $sql;

    private $db;

    private $bestList;

    private $title;

    public function __construct(int $competitionId, DBMaintainer $db)
    {
        $this->db = $db;
        $this->sql = SpecialOutputSQL::createCompetition($competitionId);
        // echo $this->sql;
        $competition = $db->getById->competition($competitionId);
        $this->title = "Resultate für Wettkampf: " . $competition->getName()->getCompetitionName() . //
        ", in: " . $competition->getLocation()->getVillage() . //
        ", am:" . DateFormatUtils::formatDateForBL($competition->getDate());
        $this->bestList = BestList::empty();
    }

    public function callDB()
    {
        $array_result = $this->db->getConn()->executeSqlToArray($this->sql);
        // Fill into Best List
        foreach ($array_result as $entry) {
            $performance = dbPerformance::performanceFromAsocArray($entry, $this->db->getConn());
            $this->bestList->addPerformance($performance);
        }
    }

    public function formatBestList()
    {
        $this->bestList->sortPerformances();
        $this->bestList->sortDisziplinOrder();
        // $this->bestList->keepBestPerformancePerPerson();
    }

    public function printTable()
    {
        echo $this->createHTMLCode();
    }

    private function createHTMLCode()
    {
        $html = "<div class='csc-header csc-header-n1'><h1 class='csc-firstHeader'>" . $this->title . "</h1></div>";
        $htmlGenerator = new HtmlGeneratorBasic(new ColumnDefinitionCategory(new CategoryUtils($this->db->getConn())), new BestListTitleFromString("Results for Competition"));
        $html .= $htmlGenerator->createOutput($this->bestList)->toString();
        return $html;
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

