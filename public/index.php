<?php
use tvustat\Competition;
use tvustat\CompetitionLocation;
use tvustat\CompetitionName;
use tvustat\DBMaintainer;

require_once '../vendor/autoload.php';


echo "Hello World";

$connection = new tvustat\ConnectionPreloaded();

$date = DateTime::createFromFormat('d/m/Y', '06/01/1996');

$person = new tvustat\Person("Florian", "Sieber", $date, $connection->getGender(1), $connection);

$db = new DBMaintainer();

echo $db->addAthlete($person);

$lmmdate19 = DateTime::createFromFormat('d/m/Y', '29/05/2019');
$bzi = new CompetitionLocation("Interlaken", "BZI", 1);
$LMMV = new CompetitionName("LMM Vorrunde", 1);
$LMMV19 = new Competition($LMMV, $bzi, $date);

echo $db->addCompetitionName($LMMV);
echo $db->addCompetitionLocation($bzi);
echo $db->addCompetition($LMMV19);

$lukas = $db->getPerson(1);
echo $lukas->getInfo();


?>