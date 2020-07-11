<?php

$_POST["athleteName"] = "Lukas Sieber";
$_POST["athleteYear"] = 1993;
$_POST["competitionName"] = "UBS Kids Cup Intern2";
$_POST["competitionLocation"] = "Interlaken2";
$_POST["competitionDate"] = "2020-07-03" ;
$_POST["disziplin"] = "100 m";
$_POST["performance"] = 10.99;
$_POST["wind"] = NULL;
$_POST["ranking"] = null;
$_POST["detail"] = NULL;

var_dump($_POST);

echo "**************************** THE RESULTS ARE *************************\n\n";

// include("insertPerformanceWithCompetition.php");