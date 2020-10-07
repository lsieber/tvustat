<?php
use tvustat\DBMaintainer;

require_once '../vendor/autoload.php';

$sql = "SELECT * FROM performances INNER JOIN competitions ON performances.competitionID = competitions.competitionID INNER JOIN disziplins ON performances.disziplinID = disziplins.disziplinID INNER JOIN athletes ON performances.athleteID = athletes.athleteID LEFT JOIN performancedetails ON performances.ID = performancedetails.performanceID INNER JOIN competitionlocations ON competitions.locationNameID = competitionlocations.competitionLocationID INNER JOIN competitionnames ON competitions.competitionNameID = competitionnames.competitionNameID";
$db = new DBMaintainer();

$mysqli = $db->getConn()->getConn();

$sql = "SELECT * FROM `athletes` WHERE `athleteID` < 20";

// $result = $mysqli->query($sql);

// $array = $result->fetch_all();

// foreach ($array as $value) {
//     var_dump($value);
//     echo "<br>";
// }

if ($result = $mysqli -> query($sql)) {
    while ($row = $result -> fetch_row()) {
        printf ("%s (%s)\n", $row[0], $row[1]);
    }
    $result -> free_result();
}

function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}


?>