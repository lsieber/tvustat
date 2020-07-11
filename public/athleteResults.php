

<?php
require_once '../vendor/autoload.php';

use tvustat\AthleteBestList;
use tvustat\DBMaintainer;
use config\dbAthletes;
$db = new DBMaintainer();
$athleteIds = $_POST["athleteIDs"];
$keepPerson = $_POST["keepPerson"];
$bl = new AthleteBestList($athleteIds, $db);
$bl->callDB();
$bl->formatBestList($keepPerson, "EANDH"); // TODO
$bl->printTable(sizeof($athleteIds) > 1);



?>

