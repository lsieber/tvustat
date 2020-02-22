

<?php
require_once '../vendor/autoload.php';

use tvustat\AthleteBestList;
use tvustat\DBMaintainer;
use config\dbAthletes;
$db = new DBMaintainer();
$athleteId = $_POST[dbAthletes::ID];
$bl = new AthleteBestList($athleteId, $db);
$bl->callDB();
$bl->formatBestList("ALL"); // TODO
$bl->printTable();



?>

