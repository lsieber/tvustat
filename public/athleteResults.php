

<?php
require_once '../vendor/autoload.php';

use tvustat\AthleteBestList;
use tvustat\DBMaintainer;
use config\dbAthletes;
$db = new DBMaintainer();
$athleteId = $_POST[dbAthletes::ID];
$keepPerson = $_POST["keepPerson"];
$bl = new AthleteBestList($athleteId, $db);
$bl->callDB();
$bl->formatBestList($keepPerson, "EANDH"); // TODO
$bl->printTable();



?>

