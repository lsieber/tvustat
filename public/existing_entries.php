    <?php
use tvustat\DBMaintainer;
use tvustat\DisziplinNameOnly;
use tvustat\AthleteNameOnly;
use tvustat\DateFormatUtils;
use config\dbCompetition;
use tvustat\Competition;
use tvustat\CompetitionLocation;
use tvustat\CompetitionName;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;

require_once '../vendor/autoload.php';

$disziplin_exists = ($_POST['type'] == 'disziplinExists') ? TRUE : FALSE;
$athlete_exists = ($_POST['type'] == 'athleteExists') ? TRUE : FALSE;
$competition_exists = ($_POST['type'] == 'competitionExists') ? TRUE : FALSE;


$competitionsInYear = ($_POST['type'] == 'competitionsForYears') ? TRUE : FALSE;

$allCompetitions = ($_POST['type'] == 'allCompetitions') ? TRUE : FALSE;
$allCompetitionNames = ($_POST['type'] == 'allCompetitionNames') ? TRUE : FALSE;
$allCompetitionLocations = ($_POST['type'] == 'allCompetitionLocations') ? TRUE : FALSE;
$allAgeCategories = ($_POST['type'] == 'allAgeCategories') ? TRUE : FALSE;
$allCategories = ($_POST['type'] == 'allCategories') ? TRUE : FALSE;
$allDisziplins = ($_POST['type'] == 'allDisziplins') ? TRUE : FALSE;
$allAthletes = ($_POST['type'] == 'allAthletes') ? TRUE : FALSE;
$allYears = ($_POST['type'] == 'allYears') ? TRUE : FALSE;


$db = new DBMaintainer();

if ($disziplin_exists) {
    $disziplinExists = $db->checkDisziplinExists(new DisziplinNameOnly($_POST["disziplin"], $db->getConn()));
    $converted_res = ($disziplinExists) ? 'true' : 'false';
    $result = array(
        "disziplinExists" => $converted_res,
        "disziplinName" => $_POST["disziplin"]
    );
    echo json_encode($result);
}

if ($athlete_exists) {
    $date = DateTime::createFromFormat("d.m.Y", $_POST["date"]);
    $athleteExists = $db->checkAthleteExists(new AthleteNameOnly($_POST["fullName"], $db->getConn()));
    $converted_res = ($athleteExists) ? 'true' : 'false';

    $result = array(
        "athleteExists" => $converted_res,
        "fullName" => $_POST["fullName"],
        "date" => DateFormatUtils::formatDateForDB($date)
        // "date" => $_POST["date"]
    );
    echo json_encode($result);
}

if ($competition_exists) {
    $date = DateTime::createFromFormat("d.m.Y", $_POST[dbCompetition::DATE]);
    $name = new CompetitionName($_POST[dbCompetitionNames::NAME]);
    $location = new CompetitionLocation($_POST[dbCompetitionLocations::VILLAGE], "");
    $competiton = new Competition($name, $location, $date);

    $competitionExists = $db->checkCompetitionExists($competiton);
    $converted_res = ($competitionExists) ? 'true' : 'false';

    $result = array(
        "competitionExists" => $converted_res,
        dbCompetitionNames::NAME => $name->getCompetitionName(),
        dbCompetition::DATE => DateFormatUtils::formatDateForDB($date),
        dbCompetitionLocations::VILLAGE => $location->getVillage()
        // "date" => $_POST["date"]
    );
    echo json_encode($result);
}

if ($allCompetitions) {
    var_dump($_POST["years"]);
    echo json_encode($db->getCompetitionsForYear($_POST["years"]));
}

if ($allCompetitions) {
    echo json_encode($db->getAllCompetitions());
}

if ($allCompetitionNames) {
    echo json_encode($db->getAllCompetitionNames());
}
if ($allCompetitionLocations) {
    echo json_encode($db->getAllCompetitionLocations());
}

if ($allAgeCategories) {
    echo json_encode($db->getAllAgeCategories());
}
if ($allCategories) {
    echo json_encode($db->getAllCategories());
}

if ($allDisziplins) {
    echo json_encode($db->getAllDisziplins());
}

if ($allAthletes) {
    echo json_encode($db->getAllAthletes());
}

if ($allYears) {
    echo json_encode($db->getAllYears());
}
?>
