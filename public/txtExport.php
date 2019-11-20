<?php
use tvustat\AthleteBestList;
use tvustat\DBMaintainer;
use tvustat\CompetitionBestList;
require_once '../vendor/autoload.php';

$_POST["top"] = null;
$_POST["yearsControl"] = "ymultiple";
$_POST["years"] = array(2019);
$_POST["categoryControl"] = "multiple";
$_POST["keepTeam"] = "YEARATHLETE";
$_POST["keepPerson"] = "ATHLETE";
$_POST["disziplins"] = array();
$_POST["outputs"] = array("txt", "printHtml");

$TXTcatS = array(array(1,2,3, 4), array(5), array(6), array(7), array(8), array(9), array(10), array(11), array(12), array(13,14,15,16));

for ($i = 0; $i < sizeof($TXTcatS); $i++) {
    $_POST["categories"] = $TXTcatS[$i];
    include 'bestList.php';
}


?>