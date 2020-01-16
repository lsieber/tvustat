<?php
use config\dbAthletes;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbDisziplin;
use config\dbPerformance;
use tvustat\Athlete;
use tvustat\CompetitionLocation;
use tvustat\CompetitionName;
use tvustat\CompetitionOnlyIds;
use tvustat\DBMaintainer;
use tvustat\Disziplin;
use tvustat\QuerryOutcome;
use tvustat\TimeUtils;
use config\dbAthleteActiveYear;
use tvustat\DateFormatUtils;
use tvustat\CompetitionUtils;
use tvustat\Performance;

require_once '../vendor/autoload.php';

$db = new DBMaintainer();
$c = $db->getConn();
$performance = $db->getPerformance($_POST[dbPerformance::ID]);
$sql = "DELETE FROM `performances` WHERE `ID` =" . $_POST[dbPerformance::ID];
$sqlResult = $c->getConn()->query($sql);
$result = new QuerryOutcome("The Performance was removed:" . $performance->getDisziplin()->getName() . ", " . $performance->getAthlete()->getFullName(). ", " . $performance->getFormatedPerformance(), false);
$result->putCustomValue("sql", $sql);
echo json_encode($result->getJSONArray());

?>