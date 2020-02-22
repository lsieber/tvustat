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
use config\dbUnsureBirthDates;

require_once '../vendor/autoload.php';

$db = new DBMaintainer();
$c = $db->getConn();
$athleteId = $_POST[dbAthletes::ID];
$unsureDate = $_POST[dbUnsureBirthDates::DATE];
$unsureYear = $_POST[dbUnsureBirthDates::YEAR];
$minYear = $_POST[dbUnsureBirthDates::MINYEAR];
$maxYear = $_POST[dbUnsureBirthDates::MAXYEAR];
$activeYear = $_POST[dbAthleteActiveYear::YEAR];
$birthDate = $_POST[dbAthletes::DATE];

$sqlUnsure = "UPDATE " . dbUnsureBirthDates::DBNAME. " SET " . dbUnsureBirthDates::DATE . "='" . bool($unsureDate) . "', " . dbUnsureBirthDates::YEAR . "='" . bool($unsureYear) . "', " . dbUnsureBirthDates::MINYEAR . "='" . nullable($minYear) . "', " .dbUnsureBirthDates::MAXYEAR . "='" . nullable($maxYear) . "' WHERE " . dbUnsureBirthDates::ID . "=". $athleteId;
$sqlActive = "UPDATE " . dbAthleteActiveYear::DBNAME. " SET " . dbAthleteActiveYear::YEAR . "='" . nullable($activeYear). "' WHERE " . dbAthleteActiveYear::ID . "=". $athleteId;
$sqlAthlete = "UPDATE " . dbAthletes::DBNAME. " SET " . dbAthletes::DATE . "='" . birthDate($birthDate). "' WHERE " . dbAthletes::ID . "=". $athleteId;

$sqlResultUnsure = $c->getConn()->query($sqlUnsure);
$sqlResultActive = $c->getConn()->query($sqlActive);
$sqlResultAthlete = $c->getConn()->query($sqlAthlete);

$result = new QuerryOutcome("The Athlete was changed:" , ($sqlResultUnsure && $sqlResultActive && $sqlResultAthlete));
$result->putCustomValue("resultUnsure", $sqlResultUnsure );
$result->putCustomValue("resultUnsureSQL", $sqlUnsure );
$result->putCustomValue("resultActive", $sqlResultActive );
$result->putCustomValue("resultActiveSQL", $sqlActive );
$result->putCustomValue("resultAthlete", $sqlResultAthlete );
$result->putCustomValue("resultAthleteSQL", $sqlAthlete );



echo json_encode($result->getJSONArray());


function nullable($v)
{
    if ($v == "") {
        return "NULL";
    }
    if ($v == NULL) {
        return "NULL";
    }
    return $v;
}

function bool($v) {
    if (is_bool($v)) {
        return $v ? 1 : 0;
    }
    if ($v == "true") {
        return 1;
    }
    if ($v == "false") {
        return 0;
    }
    return $v;
}

function birthDate($year){
    $date = null;
    if (strlen($year) == 4) {
        $str = $year . ".01.01";
        $date = DateTime::createFromFormat("Y.m.d", $str);
        $isUnsureBirthDate = TRUE;
    } else if (strlen($year) == 10) {
        $date = new DateTime($year);
    }
    return DateFormatUtils::formatDateForDB($date);
}

?>