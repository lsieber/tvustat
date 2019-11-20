<?php
use tvustat\BestListHandler;
use tvustat\DBMaintainer;
use tvustat\AgeCategory;
use tvustat\Category;

require_once '../vendor/autoload.php';

$db = new DBMaintainer();

/**
 * TESTING VALUES
 */
// $_POST["top"] = 15;
// $_POST["years"] = array(2019, 2018, 2017, 2016);
// $_POST["categories"] = array(1,2,3, 4, 5, 6);
// $_POST["categoryControl"] = "multiple";
// $_POST["keepTeam"] = "YEARATHLETE";
// $_POST["keepPerson"] = "ATHLETE";
// $_POST["disziplins"] = array(5,6,7);
/**
 * END OF TESTING VALUES
 */

/**
 * A Best List is generated based on the values stored in $_POST
 * the following parameters can be set:
 *
 * yearsControl: (string) value of {yall, ymultiple, ysingle} which defines if all years, multiple or a single year is considered
 * years: (array(int)) if yearsControl is not yall, then this parameter determines which exact years are considered
 * categoryControl: (string) value of {all, multiple, single} which defines if all categories, multiple or a single category is considered
 * categories: (array(int)) containing categoryIDs. if categoryControl is not all, then this parameter determines which exact categories are considered
 *
 * disziplins: (array(int)) containing disziplinIDs. defines which disziplins are presented. If the parameter is not set the all disziplins are shown. If the array is empty all siziplins are presented.
 * top: (int) containing a number which limits the number of results per disziplin. if top is null, then all values are shown
 *
 * keepTeam: (string) {YEARATHLETE, ATHLETE, ALL} ALL: all performances per team are presented, ATHLETE: one performance per Team is presented, YEARATHLETE: one performance per team and year is presented
 * keepPerson: (string) {YEARATHLETE, ATHLETE, ALL} ALL: all performances per athlete are presented, ATHLETE: one performance per athlete is presented, YEARATHLETE: one performance per athlete and year is presented
 *
 * outputs: (array(string) | string {html, json, txt} defines which outputs are made. if no value is set the the default is html.
 */

/**
 * Start Of the imput of values from the POST Variable whith the required tests of the variables
 */

$yearsControl = $_POST["yearsControl"];
$years = $_POST["years"];

$categoryIDs = $_POST["categories"];
$categoryControl = $_POST["categoryControl"];

$categories = array();
foreach ($categoryIDs as $key => $id) {
    $categories[$key] = $db->getConn()->getCategory($id);
}

$top = $_POST["top"];

$disziplins = (array_key_exists("disziplins", $_POST)) ? $_POST["disziplins"] : array();

/*
 * Values:
 * ALL: keeps all values of an athlete for all years
 * ATHLETE: keeps best per Athlete not woriing about the year (personal bests)
 * YEARATHLETE: keeps best per Athlete and year. This results in a list which has all saison bests in it for each athlete
 */
$keepTeam = $_POST["keepTeam"];
$keepPerson = $_POST["keepPerson"];
$keepAthlete = array();
$keepYearAthlete = array();
switch ($keepPerson) {
    case "ALL":
        break;
    case "ATHLETE":
        array_push($keepAthlete, 1); // TEAM TYPE 1 for the team
        break;
    case "YEARATHLETE":
        array_push($keepYearAthlete, 1); // TEAM TYPE 1 for the team
        break;
    default:
        echo "ERROR WE COULD NOT FIND THE DEFINED VALUE" . $keepTeam;
        break;
}
switch ($keepTeam) {
    case "ALL":
        break;
    case "ATHLETE":
        array_push($keepAthlete, 2); // TEAM TYPE 2 for the team
        break;
    case "YEARATHLETE":
        array_push($keepYearAthlete, 2); // TEAM TYPE 2 for the team
        break;
    default:
        echo "ERROR WE COULD NOT FIND THE DEFINED VALUE" . $keepTeam;
        break;
}

/*
 * BEST LIST BUILD
 *
 */

$blh = new BestListHandler($yearsControl, $years, $categoryControl, $categories, $top, $disziplins, $db);

$blh->callDB();
$blh->formatBestList($keepAthlete, $keepYearAthlete);

$outputsArray = array();
if (! array_key_exists("outputs", $_POST)) {
    array_push($outputsArray, "html");
} else {
    if (is_array($_POST["outputs"])) {
        foreach ($_POST["outputs"] as $value) {
            array_push($outputsArray, $value);
        }
    } else {
        array_push($outputsArray, $_POST["outputs"]);
    }
}

$blh->printTable($outputsArray);




