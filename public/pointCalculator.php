<?php
use config\dbDisziplin;
use config\dbGenders;
use config\dbPointSchemeNames;
use config\dbPointSchemes;
use tvustat\DBMaintainer;
use tvustat\TimeUtils;
use tvustat\pts\PointCalculator;
use config\dbPerformance;

require_once '../vendor/autoload.php';

$db = new DBMaintainer();
$calculator = new PointCalculator($db->getConn());

// $_POST[dbDisziplin::ID] = 7;
// $_POST[dbPerformance::PERFORMANCE] = 12.41;
// // $_POST[dbPointSchemes::ID] = 1;
// $_POST[dbPointSchemeNames::ID] = 1;
// $_POST[dbGenders::ID] = 1;

$disziplinId = intval($_POST[dbDisziplin::ID]);
$performance = TimeUtils::time2seconds($_POST["performance"]);

$schemeId = - 1;
if (array_key_exists(dbPointSchemes::ID, $_POST)) {
    $schemeId = intval($_POST[dbPointSchemes::ID]);
} else {
    $schemeNameId = intval($_POST[dbPointSchemeNames::ID]);
    $genderId = intval($_POST[dbGenders::ID]);
    $pointScheme = $db->getPointScheme($genderId, $schemeNameId)[0];
    $schemeId = $pointScheme[dbPointSchemes::ID];
}

$ansewer = array( //
    "points" => $calculator->calculate($disziplinId, $schemeId, $performance), //
    dbPerformance::DISZIPLINID => $disziplinId,
    dbPerformance::PERFORMANCE => $performance
);

echo json_encode($ansewer);




