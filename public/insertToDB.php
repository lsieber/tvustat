<?php
use config\dbDisziplin;
use tvustat\DBMaintainer;
use tvustat\Disziplin;
use tvustat\QuerryOutcome;
use tvustat\Athlete;
use config\dbAthletes;

require_once '../vendor/autoload.php';

$insert_disziplin = ($_POST['type'] == 'disziplin') ? TRUE : FALSE;
$insert_athlete = ($_POST['type'] == 'athlete') ? TRUE : FALSE;

$db = new DBMaintainer();
$c = $db->getConn();

if ($insert_disziplin) {
    $istime = ($_POST[dbDisziplin::ISTIME] == "true") ? true : false;
    if (! $istime) {
        if ($_POST[dbDisziplin::ISTIME] != "false") {
            echo "We have a problem with the time value of the diszipln";
        }
    }

    $disziplin = new Disziplin( //
    $_POST[dbDisziplin::NAME], //
    $c->getSorting(intval($_POST[dbDisziplin::SORTINGID])), //
    floatval($_POST[dbDisziplin::ORDER]), //
    $istime, //
    intval($_POST[dbDisziplin::DECIMAL]), //
    $c->getDisziplinType(intval($_POST[dbDisziplin::DISZIPLINTYPE])), //
    $c->getTeamType(intval($_POST[dbDisziplin::TEAMTYPEID])), //
    floatval($_POST[dbDisziplin::MINVAL]), //
    floatval($_POST[dbDisziplin::MAXVAL])); //

    /**
     * Adds The disziplin to the Database and echos the json encoded Array of a message and success value
     */
    echo json_decode($db->addDisziplin($disziplin)->getJSONArray());
}

if ($insert_athlete) {

    $athlete = new Athlete( //
    $_POST[dbAthletes::FULLNAME], //
    new DateTime($_POST[dbAthletes::DATE]), //
    $c->getGender(intval($_POST[dbAthletes::GENDERID])), //
    $c->getTeamType(intval($_POST[dbDisziplin::TEAMTYPEID]))); //
   
    /**
     * Adds The disziplin to the Database and echos the json encoded Array of a message and success value
     */
    echo json_decode($db->addAthlete($athlete)->getJSONArray());
}

?>§