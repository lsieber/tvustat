

<?php
require_once '../vendor/autoload.php';

use tvustat\AthleteBestList;
use tvustat\DBMaintainer;
use config\dbAthletes;
$db = new DBMaintainer();
$athleteIds = $_POST["athleteIDs"];
$keepPerson = $_POST["keepPerson"];
$categoryIDs = $_POST["categories"];
$categoryControl = $_POST["categoryControl"];
$categories = array();
foreach ($categoryIDs as $key => $id) {
    $categories[$key] = $db->getConn()->getCategory($id);
}

$bl = new AthleteBestList($athleteIds, $db, $categoryControl, $categories);
$bl->callDB();
$bl->formatBestList($keepPerson, "EANDH"); // TODO
$bl->printTable(sizeof($athleteIds) > 1);

?>

