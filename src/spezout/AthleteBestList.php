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

    private $athlete;

    private $top;

    public function __construct(int $athleteId, DBMaintainer $db, int $top = 30)
    {
        $this->db = $db;
        $this->top = $top;
        $this->sql = SpecialOutputSQL::createAthlete($athleteId);
        // echo $this->sql;
        $this->athlete = $db->getAthlete($athleteId);
        $this->title = new BestListTitleFromString("Resultate für: " . $this->athlete->getFullName() . //
        ", Geburtstag: " . DateFormatUtils::formatDateForBL($this->athlete->getDate()));
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

    public function formatBestList(string $keep)
    {
        $this->bestList->sortPerformances();
        $this->bestList->sortDisziplinOrder();
        $teamType = array(
            $this->athlete->getTeamType()->getId()
        );
        if ($keep == "ATHLETE") {
            $this->bestList->keepBestPerformancePerPerson($teamType);
        } elseif ($keep == "YEARATHLETE") {
            $this->bestList->keepBestPerAthleteAndYear($teamType);
        } else {
            assert($keep == "ALL");
        }
    }

    public function printTable()
    {
        $categoryUtils = new CategoryUtils($this->db->getConn());
        $columnDefCatDetail = new ColumnDefinitionCatDetail($categoryUtils);
        // $columnDefDetail = new ColumnDefinitionDetail();
        $columnDefCat = new ColumnDefinitionCategory($categoryUtils);
        // $columnDefBasic = new ColumnDefinitionBasic();

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

