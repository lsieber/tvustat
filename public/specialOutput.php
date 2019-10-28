<?php
use tvustat\AthleteBestList;
use tvustat\DBMaintainer;

$performancesForCompetitoin = ($_POST['type'] == 'competitionList') ? TRUE : FALSE;
$resultsAthlete = ($_POST['type'] == 'resultsAthlete') ? TRUE : FALSE;

$db = new DBMaintainer();
if ($performancesForCompetitoin) {
    $competitionID = $_POST["competitionID"];
    $bl = new CompetitoinBestList($competitionID, $db);
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