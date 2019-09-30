<?php
use tvustat\DBMaintainer;
use tvustat\DisziplinNameOnly;
use tvustat\AthleteNameOnly;

require_once '../vendor/autoload.php';

$disziplin_exists = ($_POST['type'] == 'disziplinExists') ? TRUE : FALSE;
$athlete_exists = ($_POST['type'] == 'athleteExists') ? TRUE : FALSE;

$db = new DBMaintainer();

if ($disziplin_exists) {
    $disziplinExists = $db->checkDisziplinExists(new DisziplinNameOnly($_POST["disziplin"], $db->getConn()));
    $converted_res = ($disziplinExists) ? 'true' : 'false';
    $result = array("disziplinExists"=> $converted_res, "disziplinName" => $_POST["disziplin"]);
    echo json_encode($result);
}

if ($athlete_exists) {
    $athleteExists = $db->checkAthleteExists(new AthleteNameOnly($_POST["fullName"], $db->getConn()));
    $converted_res = ($athleteExists) ? 'true' : 'false';
    $result = array("athleteExists"=> $converted_res, "fullName" => $_POST["fullName"]);
    echo json_encode($result);
}

?>
