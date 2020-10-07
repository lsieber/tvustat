<?php
namespace tvustat;

use config\dbPerformance;
use config\dbDisziplin;

function convert($size)
{
    $unit = array(
        'b',
        'kb',
        'mb',
        'gb',
        'tb',
        'pb'
    );
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

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

    private $isOnlyOneDisziplin;

    private $primitiveBestList = array();

    public function __construct(string $yearsControl, array $years, string $categoryControl, array $categories, string $top = Null, array $disziplins, DBMaintainer $db)
    {
        $this->top = $top;
        $this->db = $db;
        $this->sql = OutputSQL::create($categoryControl, $categories, $disziplins, $years, $yearsControl);
        $this->teamSQL = OutputSQL::createTeam($categoryControl, $categories, $disziplins, $years, $yearsControl);
        $this->title = new BestListTitleBasic($categoryControl, $categories, $yearsControl, $years, $top, $disziplins);
        $this->bestList = BestList::empty();
        $this->isOnlyOneDisziplin = sizeof($disziplins) == 1;
        // echo $this->sql;
    }

    public function callDB()
    {
        // Athletes
        // Create SQL, Call DB
        // echo $this->sql;
        // $result = $this->db->getConn()->getConn()->query($this->sql);
        // $dbquery = $this->db->getConn()->getConn()->query($this->sql);
        // $dbquery->execute();

        // Fill into Best List
        // foreach ($result->fetch_row() as $entry) {
        echo "before get Conn: " . convert(memory_get_usage()) . "<br>";

        $mysqli = $this->db->getConn()->getConn();
        echo convert(memory_get_usage()) . "<br>";

        $disziplins = $this->db->getAll->disziplinsClasses();

        $counter = 0;
        $mod = 2;

        foreach ($disziplins as $disziplin) {
            $sql = $this->sql . " AND ". dbPerformance::DBNAME .".". dbPerformance::DISZIPLINID ."=".$disziplin->getId();
            if ($result = $mysqli->query($sql)) {

                while ($row = $result->fetch_assoc()) {
                    // echo ($row[dbAthletes::FULLNAME]);
                    // This is only an example, the numbers below will
                    // differ depending on your system
                    $performance = dbPerformance::array2Elmt($row, $this->db->getConn());
                    // array_push($this->primitiveBestList, [$performance->getId(), $performance->getAthlete()->getId(), $performance->getDisziplin()->getId()]);
                    $this->bestList->addPerformance($performance);
                    $counter = $counter + 1;
                    if ($counter % $mod == 0) {
                        echo "Performance Nr " . $counter . ": " . convert(memory_get_usage()) . "<br>";
                        $mod = $counter;
                    }
                }

                $result->free_result();
            }
        }
        echo "after calls".convert(memory_get_usage()) . "\n";

        // while($row = $dbquery->fetch_assoc()) {
        // //var_dump($row);
        // $performance = dbPerformance::array2Elmt($row, $this->db->getConn());
        // $this->bestList->addPerformance($performance);
        // }

        if (! is_null($this->teamSQL)) {
            // TEAMS
            // Create SQL, Call DB
            // echo $this->teamSQL;

            $team_result = $this->db->getConn()->executeSqlToArray($this->teamSQL);

            // Fill into Best List
            foreach ($team_result as $entry) {
                $performance = dbPerformance::array2Elmt($entry, $this->db->getConn());
                $this->bestList->addPerformance($performance);
            }
        }
    }

    public function formatBestList(array $keepAthlete, array $keepYearAthlete, string $manualTiming)
    {
        $this->bestList->sortPerformances();
        $this->bestList->sortDisziplinOrder();
        $this->bestList->keepBestPerformancePerPerson($keepAthlete, $manualTiming);
        $this->bestList->keepBestPerAthleteAndYear($keepYearAthlete, $manualTiming);
        if ($manualTiming == "H") {
            $this->bestList->keepOnlyManual();
        } else if ($manualTiming == "E") {
            $this->bestList->keppOnlyElectrical();
        }
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
        $columnDefCatWind = new ColumnDefinitionCatWindNameLink($categoryUtils);
        // WANT TO INSERT WIND??? then add the line below in the html part and change the column defintion accordingliy
        // $columnDefBasic = new ColumnDefinitionBasic();

        if (sizeof($outputs) == 0) {
            array_push($outputs, "html");
        }

        foreach ($outputs as $output) {

            if ($output == "html") {
                $htmlGenerator = new HtmlGeneratorDisziplinIndiv($columnDefCatDetail, $columnDefCat, $columnDefCat, $this->title);
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

            if ($output == "txtAsString") {
                $txtGenerator = new TxtGeneratorAsString($this->title);
                echo $txtGenerator->createOutput($this->bestList)->toString();
            }

            if ($output == "printHtml") {
                $htmlGenerator = new HtmlGeneratorDisziplinIndiv($columnDefCatDetail, $columnDefCat, $columnDefCatWind, $this->title);
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

