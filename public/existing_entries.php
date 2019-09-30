<?php
use tvustat\DBMaintainer;
use tvustat\DisziplinNameOnly;
use tvustat\AthleteNameOnly;
use tvustat\DateFormatUtils;

require_once '../vendor/autoload.php';

$disziplin_exists = ($_POST['type'] == 'disziplinExists') ? TRUE : FALSE;
$athlete_exists = ($_POST['type'] == 'athleteExists') ? TRUE : FALSE;
$allCompetitionNames = ($_POST['type'] == 'allCompetitionNames') ? TRUE : FALSE;
$allCompetitionLocations = ($_POST['type'] == 'allCompetitionLocations') ? TRUE : FALSE;

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
//         "date" => $_POST["date"]
    );
    echo json_encode($result);
}

if ($allCompetitionNames) {
    echo json_encode($db->getAllCompetitionNames());
}
if ($allCompetitionLocations) {
    echo json_encode($db->getAllCompetitionLocations());
}
?>
