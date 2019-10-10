<?php
namespace tvustat;

use config\dbPerformance;

class BestListHandler
{

    // INPUT Variables.
    // private $years;

    // private $gender;

    // private $categories;
    private $top;

    // private $disziplins;

    // Internal Variables
    private $sql;
    private $teamSQL;

    private $db;

    private $bestList;

    private $title;

    public function __construct(array $years, string $categoryControl, array $categories, string $top, array $disziplins, DBMaintainer $db)
    {
        // $this->years = $years;
        // $this->gender = $gender;
        // $this->categories = $categories;
        // $this->disziplins = $disziplins;
        
        $this->top = $top;
        $this->db = $db;
        $this->sql = OutputSQL::create($categoryControl, $categories, $disziplins, $years);
        $this->teamSQL = OutputSQL::createTeam($categoryControl, $categories, $disziplins, $years);
        $this->title = new BestListTitle($categoryControl, $categories, $years, $top, $disziplins);
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
            $performance = dbPerformance::performanceFromAsocArray($entry, $this->db->getConn());
            $this->bestList->addPerformance($performance);
        }
        
        if (!is_null($this->teamSQL)) {
            // TEAMS
            // Create SQL, Call DB
            $team_result = $this->db->getConn()->executeSqlToArray($this->teamSQL);
            
            // Fill into Best List
            foreach ($team_result as $entry) {
                $performance = dbPerformance::performanceFromAsocArray($entry, $this->db->getConn());
                $this->bestList->addPerformance($performance);
            }
        }
    }

    public function formatBestList()
    {
        $this->bestList->sortPerformances();
        $this->bestList->sortDisziplinOrder();
        $this->bestList->keepBestPerformancePerPerson();
        $this->bestList->setTop($this->top);
    }

    public function printTable()
    {
        echo $this->createHTMLCode();
    }

    public function printHTMLCode()
    {
        $s = str_replace("&", "&amp;", $this->createHTMLCode());
        $s = str_replace("<", "&lt;", $s);
        $s = str_replace(">", "&gt;", $s);
        echo ("<pre>" . $s . "</pre>" . "</br>" . "</br>");
    }

    private function createHTMLCode()
    {
        $categoryUtils = new CategoryUtils($this->db->getConn());
        
        $html = "<div class='csc-header csc-header-n1'><h1 class='csc-firstHeader'>" . $this->title->getTitle() . "</h1></div>";
        $html .= ($this->top == 1) ? $this->bestList->createHTMLRecord() : $this->bestList->createHTMLBestList($categoryUtils);
        return $html;
    }

    public function createTXT()
    {
        $title = $this->title->getTxtFileTitle();
        $path = $this->createTXTFilePath($title);

        $result = $this->bestList->createTXT($path, $title);

        echo ($result . ", path: " . $path);
    }

    private function createTXTFilePath(string $title)
    {
        $stufe = array();
        $path = "";
        // Create A TXT File for the usage in other programs (tab deliminated)
        if (sizeof($this->category) == 1 && sizeof($this->year == 1) && $this->top == 1001) {
            $stufe[0] = "Bestenlisten/EinJahrEineKategorie";
            $stufe[1] = implode("/", [
                $stufe[0],
                $this->year[0]
            ]);
            $stufe[2] = implode("/", [
                $stufe[1],
                $this->category[0]
            ]);
            if (! is_dir($stufe[2])) {
                if (! is_dir($stufe[1])) {
                    if (! is_dir($stufe[0])) {
                        mkdir($stufe[0]);
                    }
                    mkdir($stufe[1]);
                }
                mkdir($stufe[2]);
            }
            $path = $stufe[2] . "/" . $title . ".txt";
        } else {
            $path = "Bestenlisten/Andere Bestenlisten/" . $title . ".txt";
        }
        return $path;
    }

    // private function createLaufDependantSQL($lauf)
    // {
    // // TODO rework this code
    // $lauf_true = Conventions::isLauf($lauf);
    // if (Conventions::isSingleEvent($lauf)) { // For single events the age of the People is dependant on the year and the kategorie.
    // $this->sql->statement_age_array($this->category);
    // } else { // For Team events the year has to be equal to the age (Jg). This information is stored together with the sex and the kategory in the Mitglied ID of the Team: ID = 12345 means: 1: placeholder, 2:sex: 3=female, 4=male, 5=mixed, 3:kagegorie:"1"=>"10", "2"=>"12", "3"=>"14","4"=>"16","5"=> "18", "6"=>"20", "7"=>"aktiv", 45: year from 1950 onwards; year-1950 = 45.
    // $MitgliedIDs = array();
    // $categories = (array_key_exists(Categories::ALL, array_flip($this->category))) ? array_keys(Categories::CATEGORYDICTIONARY) : $this->category;
    // foreach ($categories as $kat) {
    // $years = ($this->year[0] == "all") ? range(1950, 2049) : $this->year;
    // foreach ($years as $jahr) {
    // $sex_array = ($this->gender->getNumericalValue() == 3) ? array(
    // 1,
    // 2,
    // 3
    // ) : array(
    // $this->gender->getNumericalValue(),
    // 3
    // );
    // foreach ($sex_array as $sex) {
    // $id = 10000 + 1000 * $sex + 100 * Categories::CATEGORYDICTIONARY[$kat][Categories::TEAMID] + $jahr - 1950;
    // array_push($MitgliedIDs, $id);
    // }
    // }
    // }
    // $this->sql->set_where_age("");
    // $this->sql->set_where_mitglied($this->sql->statement_IN_array($MitgliedIDs, 'Mitglied', 'AND'));
    // }
    // $st_lauf = $this->sql->statement_equal_value($lauf, 'Lauf');
    // $this->sql->set_where_lauf($st_lauf);
    // $this->sql->combine_where("WHERE"); // combines all previous where statements into one WHERE statement which is stored in the class and used for the final SQL querry
    // $asc_desc = $lauf_true ? " ASC" : " DESC";
    // $this->sql->set_order('Lauf, Laufsort, Disziplin, Leistung' . $asc_desc . ', Datum, DisziplinID, b.ID');
    // $this->sql->set_group_by(array(
    // "DisziplinID, Mitglied"
    // ));
    // }

    /**
     *
     * @return BestListTitle
     */
    public function getTitle()
    {
        return $this->title;
    }
}

