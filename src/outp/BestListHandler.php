<?php
namespace tvustat;

use config\dbPerformance;

class BestListHandler
{

    // INPUT Variables.
    private $top;

    // Internal Variables
    private $sql;

    private $teamSQL;

    /**
     *
     * @var DBMaintainer
     */
    private $db;

    /**
     *
     * @var BestList
     */
    private $bestList;

    /**
     *
     * @var BestListTitle
     */
    private $title;

    public function __construct(string $yearsControl, array $years, string $categoryControl, array $categories, string $top = Null, array $disziplins, DBMaintainer $db)
    {
        $this->top = $top;
        $this->db = $db;
        $this->sql = OutputSQL::create($categoryControl, $categories, $disziplins, $years, $yearsControl);
        $this->teamSQL = OutputSQL::createTeam($categoryControl, $categories, $disziplins, $years, $yearsControl);
        $this->title = new BestListTitleBasic($categoryControl, $categories, $yearsControl, $years, $top, $disziplins);
        $this->bestList = BestList::empty();
    }

    public function callDB()
    {
        // Athletes
        // Create SQL, Call DB
//         echo $this->sql;
        $array_result = $this->db->getConn()->executeSqlToArray($this->sql);

        // Fill into Best List
        foreach ($array_result as $entry) {
            $performance = dbPerformance::array2Elmt($entry, $this->db->getConn());
            $this->bestList->addPerformance($performance);
        }

        if (! is_null($this->teamSQL)) {
            // TEAMS
            // Create SQL, Call DB
//             echo $this->teamSQL;
            
            $team_result = $this->db->getConn()->executeSqlToArray($this->teamSQL);

            // Fill into Best List
            foreach ($team_result as $entry) {
                $performance = dbPerformance::array2Elmt($entry, $this->db->getConn());
                $this->bestList->addPerformance($performance);
            }
        }
    }

    public function formatBestList(array $keepAthlete, array $keepYearAthlete)
    {
        $this->bestList->sortPerformances();
        $this->bestList->sortDisziplinOrder();
        $this->bestList->keepBestPerformancePerPerson($keepAthlete);
        $this->bestList->keepBestPerAthleteAndYear($keepYearAthlete);
    }

    /**
     *
     * @param array[string] $outputs
     */
    public function printTable(array $outputs)
    {
        $categoryUtils = new CategoryUtils($this->db->getConn());
        $columnDefCatDetail = new ColumnDefinitionCatDetailNameLink($categoryUtils);
        // $columnDefDetail = new ColumnDefinitionDetail();
        $columnDefCat = new ColumnDefinitionCategoryNameLink($categoryUtils);
        // $columnDefBasic = new ColumnDefinitionBasic();

        if (sizeof($outputs) == 0) {
            array_push($outputs, "html");
        }

        foreach ($outputs as $output) {

            if ($output == "html") {
                $htmlGenerator = new HtmlGeneratorDisziplinIndiv($columnDefCatDetail, $columnDefCat, $this->title);
                $html = $htmlGenerator->createOutput($this->bestList, $this->top);
                echo $html->toString();
            }

            if ($output == "json") {
                $jsonGenerator = new JsonGenerator($columnDefCatDetail);
                $json = $jsonGenerator->createOutput($this->bestList, $this->top);
                echo $json->toString();
                echo "TEEEET";
            }

            if ($output == "txt") {
                $txtGenerator = new TxtGenerator($this->title);
                $txtGenerator->createOutput($this->bestList);
            }

            if ($output == "printHtml") {
                $htmlGenerator = new HtmlGeneratorDisziplinIndiv($columnDefCatDetail, $columnDefCat, $this->title);
                $html = $htmlGenerator->createOutput($this->bestList, $this->top);
                echo "</br>" . $this->title->getTitle() . "</br>";
                self::printHTMLCode($html->toString());
            }
        }
    }

    private static function printHTMLCode(string $string)
    {
        $s = str_replace("&", "&amp;", $string);
        $s = str_replace("<", "&lt;", $s);
        $s = str_replace(">", "&gt;", $s);
        echo ("<pre>" . $s . "</pre>" . "</br>" . "</br>");
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

