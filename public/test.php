<?php
use tvustat\DBMaintainer;
use config\dbPerformance;
use config\dbBirthDateExeptions;
require_once '../vendor/autoload.php';


$db = new DBMaintainer();

$athleteName = "Timo Fahrenbruch";
$birthDate = DateTime::createFromFormat("Y-m-d", "2002-11-30");

$test = dbBirthDateExeptions::isAthleteException($athleteName, $birthDate, $db->getConn());

echo "Result if Timo Exeption Exists = " . var_dump($test);



// $_POST["type"] = "performance";

// // 11: Timo
// // 12: Lukas
// // 13: Marc
// $_POST[dbPerformance::ATHLETEID] = 13;

// // 10/11: Hochdorf
// // 12: Sarnen Fr�hling
// $_POST[dbPerformance::COMPETITOINID] = 11;

// // 3: Kids Cup
// // 4: H�rden
// // 9: 1000m
// // 13: Weit Zone
// // 14: 5x frei
// // 20: kugel
// $_POST[dbPerformance::DISZIPLINID] = 4;

// $_POST[dbPerformance::PERFORMANCE] = 16.1;
// $_POST[dbPerformance::WIND] = NULL;
// $_POST[dbPerformance::PLACE] = "1rt";

// include ('insertToDB.php');

// $answer_Post = json_decode($_POST);
// var_dump($answer_Post);
// $answer = $answer_Post ["type"];

// $answer = "TEST";
// echo json_encode( array("answer"=>$answer));
// echo "</br>";

// $db = new DBMaintainer();

// echo "Athlete:";
// print_r($db->checkAthleteIDExists(12));
// echo "</br>";
// echo "Disziplin:";
// var_dump($db->checkDisziplinIDExists(142));
// echo "</br>";

// echo "Competition:" . $db->checkCompetitionIDExists(412);
// echo "</br>";

?>