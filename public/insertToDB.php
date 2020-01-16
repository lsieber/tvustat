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

$insert_disziplin = ($_POST['type'] == 'disziplin') ? TRUE : FALSE;
$insert_athlete = ($_POST['type'] == 'athlete') ? TRUE : FALSE;
$insert_competionName = ($_POST['type'] == 'competitionName') ? TRUE : FALSE;
$insert_competionLocation = ($_POST['type'] == 'competitionLocation') ? TRUE : FALSE;
$insert_competion = ($_POST['type'] == 'competition') ? TRUE : FALSE;
$insert_performance = ($_POST['type'] == 'performance') ? TRUE : FALSE;

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
    echo json_encode($db->addDisziplin($disziplin)->getJSONArray());
}

if ($insert_athlete) {
    /**
     * Prepatration of the unsure birth dates for later insertation
     */
    $isUnsureBirthDate = FALSE;
    $isUnsureBirthYear = FALSE;
    $minYear = null;
    $maxYear = null;
    if (array_key_exists(dbUnsureBirthDates::MINYEAR, $_POST)) {
        if ($_POST[dbUnsureBirthDates::MINYEAR] != NULL) {
            $minYear = $_POST[dbUnsureBirthDates::MINYEAR];
            $isUnsureBirthYear = TRUE;
        }
    }
    if (array_key_exists(dbUnsureBirthDates::MAXYEAR, $_POST)) {
        if ($_POST[dbUnsureBirthDates::MAXYEAR] != NULL) {
            $maxYear = $_POST[dbUnsureBirthDates::MAXYEAR];
            $isUnsureBirthYear = TRUE;
        }
    }

    /**
     * Preparation of the other Athlete Attributes
     */
    $date = null;
    if (strlen($_POST[dbAthletes::DATE]) == 4) {
        $str = $_POST[dbAthletes::DATE] . ".01.01";
        $date = DateTime::createFromFormat("Y.m.d", $str);
        $isUnsureBirthDate = TRUE;
    } else if (strlen($_POST[dbAthletes::DATE]) == 10) {
        $date = new DateTime($_POST[dbAthletes::DATE]);
    }
//     echo DateFormatUtils::formatDateForBL($date);
    $athlete = new Athlete( //
    $_POST[dbAthletes::FULLNAME], //
    $date, //
    $c->getGender(intval($_POST[dbAthletes::GENDERID])), //
    $c->getTeamType(intval($_POST[dbDisziplin::TEAMTYPEID]))); //

    /**
     * Adds The athlete to the Database and echos the json encoded Array of a message and success value
     */
    
    $querry = $db->addAthlete($athlete);

    $querry->putCustomValue("m2", $isUnsureBirthDate);
    $querry->putCustomValue("m3", $isUnsureBirthYear);
    $querry->putCustomValue("minYear", $minYear);
    $querry->putCustomValue("maxYear", $maxYear);
    
    if (array_key_exists(dbAthleteActiveYear::YEAR, $_POST) && $querry->getSuccess()) {
        $value = $db->addAthleteActiveYear($querry->getCustomValue(dbAthletes::getIDString()), intval($_POST[dbAthleteActiveYear::YEAR]));
        $querry->putCustomValue("active year Result", $value);
    }
    $querry->putCustomValue(dbAthletes::FULLNAME, $_POST[dbAthletes::FULLNAME]);
    $querry->putCustomValue(dbAthletes::DATE, $_POST[dbAthletes::DATE]);

    /**
     * Add Unsure Birth Date
     */

    if (($isUnsureBirthDate || $isUnsureBirthYear) && $querry->getSuccess()) {
        $querry->putCustomValue("m1", "We are adding a unsure birth date");
        $value = $db->addUnsureBirthDate($querry->getCustomValue(dbAthletes::getIDString()), $isUnsureBirthDate, $isUnsureBirthYear, $minYear, $maxYear);
        $querry->putCustomValue("Unsure Birth Date Result", $value);
    }

    echo json_encode($querry->getJSONArray());
}

if ($insert_competionName) {
    $competitionName = new CompetitionName($_POST[dbCompetitionNames::NAME]); //
    $querry = $db->addCompetitionName($competitionName);
    $json = $querry->getJSONArray();
    echo json_encode($json);
    // echo json_encode($db->addCompetitionName($competitionName)->getJSONArray());
}
if ($insert_competionLocation) {
    $competitionLocation = new CompetitionLocation( //
    $_POST[dbCompetitionLocations::VILLAGE], //
    $_POST[dbCompetitionLocations::FACILITY]);

    echo json_encode($db->addCompetitionLocation($competitionLocation)->getJSONArray());
}
if ($insert_competion) {
    $competition = CompetitionOnlyIds::create( //
    $_POST[dbCompetition::NAMEID], //
    $_POST[dbCompetition::LOCATIONID], //
    new DateTime($_POST[dbCompetition::DATE]));

    echo json_encode($db->addCompetition($competition)->getJSONArray());
}

if ($insert_performance) {
    $athleteEx = $db->checkAthleteIDExists($_POST[dbPerformance::ATHLETEID]);
    $compEx = $db->checkCompetitionIDExists($_POST[dbPerformance::COMPETITOINID]);
    $disEx = $db->checkDisziplinIDExists($_POST[dbPerformance::DISZIPLINID]);

    $result = new QuerryOutcome("NO RESULT! CHECK THE FUNCTION", FALSE);
    if ($athleteEx && $compEx && $disEx) {

        $disziplin = $db->getDisziplin($_POST[dbPerformance::DISZIPLINID]);
        $athlete = $db->getAthlete($_POST[dbPerformance::ATHLETEID]);
        $competition = $db->getCompetition($_POST[dbPerformance::COMPETITOINID]);

        $perfModified = ($disziplin->isTime()) ? TimeUtils::time2seconds($_POST["performance"]) : $_POST["performance"];

        $minValueOk = $perfModified >= $disziplin->getMinValue();
        $maxValueOk = $perfModified <= $disziplin->getMaxValue();
        $teamTypeMatches = $athlete->getTeamType()->getId() == $disziplin->getTeamType()->getId();
        $forcedEntry = (isset($_POST['forced'])) ? $_POST['forced'] == "true" : FALSE;

        if (($minValueOk && $maxValueOk && $teamTypeMatches) || ($forcedEntry)) {
            if (! $db->checkPerformanceByIds($_POST)) {
                $existingPerformances = $db->loadPerformanceAthleteYear($disziplin->getId(), $athlete->getId(), DateFormatUtils::formatDateaAsYear($competition->getDate()));
                $performanceExists = false;
                if (CompetitionUtils::isFromTVUBuch($competition)) {
                    if (sizeof($existingPerformances) > 0) {

                        $result = new QuerryOutcome("The entered Performance does identically exist allready in A normal competition and not from The TVu Buch", false);
                        foreach ($existingPerformances as $value) {
                            if ($disziplin->getSorting()->sortASC()) {
                                if ($value[dbPerformance::PERFORMANCE] <= $perfModified) {
                                    $performanceExists = true;
                                }
                            } else {
                                if ($value[dbPerformance::PERFORMANCE] >= $perfModified) {
                                    $performanceExists = true;
                                }
                            }
                        }
                    }
                }
                if (! $performanceExists) {
                    $result = $db->addPerformanceWithIdsOnly($_POST);
                    $newPerf = $db->getPerformance($result->getCustomValue(dbPerformance::getIDString()));

                    $result->putCustomValue(dbAthletes::FULLNAME, $newPerf->getAthlete()
                        ->getFullName());
                    $result->putCustomValue(dbDisziplin::NAME, $newPerf->getDisziplin()
                        ->getName());
                    $result->putCustomValue(dbPerformance::PERFORMANCE, $newPerf->getPerformance());

                    foreach ($existingPerformances as $performanceRaw) {
                        if ($performanceRaw[dbPerformance::PERFORMANCE] == $newPerf->getPerformance()) {
                            $compExisting = $db->getCompetition($performanceRaw[dbPerformance::COMPETITOINID]);
                            if (CompetitionUtils::isFromTVUBuch($compExisting)) {
                                $db->removePerformance($performanceRaw[dbPerformance::ID]);
                                $result->putCustomValue("REMOVED SAME TVU BUCH ENTRY", $performanceRaw[dbPerformance::PERFORMANCE]);
                            }
                        }
                    }
                }
            } else {
                $result = new QuerryOutcome("The entered Performance does identically exist allready", false);
            }
        } else {
            $result = new QuerryOutcome("The entered Performance does not meet the specifications of the minimal and maximal Value of the Disziplin or the Team Type does not match. You can carry out the insertation with the additional argument 'forced':'true'.", FALSE);
            $result->putCustomValue(dbDisziplin::MINVAL, $disziplin->getMinValue());
            $result->putCustomValue(dbDisziplin::MAXVAL, $disziplin->getMaxValue());
            $result->putCustomValue("enteredValue", $_POST[dbPerformance::PERFORMANCE]);
            $result->putCustomValue("disziplinTeamType", $disziplin->getTeamType()
                ->getType());
            $result->putCustomValue("athleteTeampType", $athlete->getTeamType()
                ->getType());
        }
    } else {
        $result = new QuerryOutcome("The given atheteId, competitionId or disziplinId does not exist.", FALSE);
        $result->putCustomValue("athleteExists", $athleteEx);
        $result->putCustomValue("disziplinExists", $disEx);
        $result->putCustomValue("competitionExists", $compEx);
    }
    if (isset($_POST["fromFile"])) {
        $result->putCustomValue("fromFile", $_POST["fromFile"]);
    }
    echo json_encode($result->getJSONArray());
}

?>