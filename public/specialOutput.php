<?php
use tvustat\AthleteBestList;
use tvustat\DBMaintainer;
use tvustat\CompetitionBestList;
require_once '../vendor/autoload.php';

$performancesForCompetitoin = ($_POST['type'] == 'competitionList') ? TRUE : FALSE;
$resultsAthlete = ($_POST['type'] == 'resultsAthlete') ? TRUE : FALSE;

$db = new DBMaintainer();

if ($performancesForCompetitoin) {
    $competitionID = $_POST["competitionID"];
    $bl = new CompetitionBestList($competitionID, $db);
    $bl->callDB();
    $bl->formatBestList();
    $bl->printTable();
}

if ($resultsAthlete) {
    $athleteId = $_POST["athleteID"];
    $bl = new AthleteBestList($athleteId, $db);
    $bl->callDB();
    $bl->formatBestList("ALL"); // TODO
    $bl->printTable();
}

?>