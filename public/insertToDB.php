<?php
use config\dbAthleteActiveYear;
use config\dbAthletes;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbDisziplin;
use config\dbPerformance;
use config\dbUnsureBirthDates;
use tvustat\Athlete;
use tvustat\CompetitionLocation;
use tvustat\CompetitionName;
use tvustat\CompetitionUtils;
use tvustat\DBMaintainer;
use tvustat\DateFormatUtils;
use tvustat\Disziplin;
use tvustat\QuerryOutcome;
use tvustat\TimeUtils;
use tvustat\Performance;
use config\dbPerformanceDetail;
use tvustat\WindUtils;
use tvustat\DBInputUtils;
use tvustat\PostUtils;
use tvustat\StringConversionUtils;
use tvustat\Competition;

require_once '../vendor/autoload.php';

$insert_disziplin = ($_POST['type'] == 'disziplin') ? TRUE : FALSE;
$insert_athlete = ($_POST['type'] == 'athlete') ? TRUE : FALSE;
$insert_competionName = ($_POST['type'] == 'competitionName') ? TRUE : FALSE;
$insert_competionLocation = ($_POST['type'] == 'competitionLocation') ? TRUE : FALSE;
$insert_competion = ($_POST['type'] == 'competition') ? TRUE : FALSE;
$insert_competitionFromValues = ($_POST['type'] == 'competitionFromValue') ? TRUE : FALSE;
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
    echo json_encode($db->add->disziplin($disziplin)->getJSONArray());
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
    // echo DateFormatUtils::formatDateForBL($date);

    $license = PostUtils::value(dbAthletes::lICENCE);
    $said = PostUtils::value(dbAthletes::SAID);

    $athlete = new Athlete( //
    $_POST[dbAthletes::FULLNAME], //
    $date, //
    $c->getGender(intval($_POST[dbAthletes::GENDERID])), //
    $c->getTeamType(intval($_POST[dbDisziplin::TEAMTYPEID])), //
    NULL, // TeamCategory
    NULL, // Athlete Id
    $license, // licenseNumber
    $said); // said

    /**
     * Adds The athlete to the Database and echos the json encoded Array of a message and success value
     */

    $querry = $db->add->athlete($athlete);

    $querry->putCustomValue("m2", $isUnsureBirthDate);
    $querry->putCustomValue("m3", $isUnsureBirthYear);
    $querry->putCustomValue("minYear", $minYear);
    $querry->putCustomValue("maxYear", $maxYear);

    if (array_key_exists(dbAthleteActiveYear::YEAR, $_POST) && $querry->getSuccess()) {
        $value = $db->add->athleteActiveYear($querry->getCustomValue(dbAthletes::getIDString()), intval($_POST[dbAthleteActiveYear::YEAR]));
        $querry->putCustomValue("active year Result", $value);
    }
    $querry->putCustomValue(dbAthletes::FULLNAME, $_POST[dbAthletes::FULLNAME]);
    $querry->putCustomValue(dbAthletes::DATE, $_POST[dbAthletes::DATE]);

    /**
     * Add Unsure Birth Date
     */

    if (($isUnsureBirthDate || $isUnsureBirthYear) && $querry->getSuccess()) {
        $querry->putCustomValue("m1", "We are adding a unsure birth date");
        $value = $db->add->unsureBirthDate($querry->getCustomValue(dbAthletes::getIDString()), $isUnsureBirthDate, $isUnsureBirthYear, $minYear, $maxYear);
        $querry->putCustomValue("Unsure Birth Date Result", $value);
    }

    echo json_encode($querry->getJSONArray());
}

if ($insert_competionName) {
    $competitionName = new CompetitionName($_POST[dbCompetitionNames::NAME]); //
    $querry = $db->add->competitionName($competitionName);
    $json = $querry->getJSONArray();
    echo json_encode($json);
    // echo json_encode($db->addCompetitionName($competitionName)->getJSONArray());
}
if ($insert_competionLocation) {
    $competitionLocation = new CompetitionLocation( //
    $_POST[dbCompetitionLocations::VILLAGE], //
    $_POST[dbCompetitionLocations::FACILITY]);

    echo json_encode($db->add->competitionLocation($competitionLocation)->getJSONArray());
}
if ($insert_competion) {
    $competition = CompetitionOnlyIds::create( //
    $_POST[dbCompetition::NAMEID], //
    $_POST[dbCompetition::LOCATIONID], //
    new DateTime($_POST[dbCompetition::DATE]));

    echo json_encode($db->add->competition($competition)->getJSONArray());
}
if ($insert_competitionFromValues) {
    $name = $_POST[dbCompetitionNames::NAME];
    if ($db->getbyValues->competitionName($name) == NULL) {
        $querry = $db->add->competitionName(new CompetitionName($name));
    }
    $village = $_POST[dbCompetitionLocations::VILLAGE];
    $facility = $_POST[dbCompetitionLocations::FACILITY];
    if ($db->getbyValues->competitionLocation($village) == NULL){
        $db->add->competitionLocation(new CompetitionLocation($village, $facility));
    }
    
    $competitionName = $db->getbyValues->competitionName($name);
    $competitionLocation = $db->getbyValues->competitionLocation($village);
    $date = new DateTime($_POST[dbCompetition::DATE]);
    
    $competition = new Competition($competitionName, $competitionLocation, $date);
    
    echo json_encode($db->add->competition($competition)->getJSONArray());
}



// ******************************************************************************************
// ******************** Performance Input ***************************************************
// ******************************************************************************************
if ($insert_performance) {
    $athlete = $db->getById->athlete($_POST[dbPerformance::ATHLETEID]);
    $competition = $db->getById->competition($_POST[dbPerformance::COMPETITOINID]);
    $disziplin = $db->getById->disziplin($_POST[dbPerformance::DISZIPLINID]);

    $performanceResult = DBInputUtils::formatPerformanceToFloat($disziplin->isTime(), $_POST[dbPerformance::PERFORMANCE]);
    $wind = PostUtils::value(dbPerformance::WIND);
    $wind = is_null($wind) || $wind == ""? NULL: floatval($wind);
    $ranking = PostUtils::value(dbPerformance::PLACE);
    $manualTiming = StringConversionUtils::stringTrueFalseToBool($_POST[dbPerformance::MANUALTIME]);
    $sourceId = intval($_POST[dbPerformance::SOURCE]);
    $source = $db->getConn()->getSource($sourceId);
    $detail = PostUtils::value(dbPerformanceDetail::DETAIL);
    $forcedEntry = (isset($_POST['forced'])) ? $_POST['forced'] == "true" : FALSE;

    $performance = new Performance($disziplin, $athlete, $competition, $performanceResult, $wind, $ranking, $manualTiming, $source, NULL, $detail);

    $result = insertPerformance($performance, $forcedEntry, $db);

    echo json_encode($result->getJSONArray());
}

function insertPerformance(Performance $performance, bool $forcedEntry, DBMaintainer $db)
{
    $athlete = $performance->getAthlete();
    $disziplin = $performance->getDisziplin();
    $competition = $performance->getCompetition();

    $result = new QuerryOutcome("NO RESULT! CHECK THE FUNCTION", FALSE);
    if (! is_null($athlete) && ! is_null($competition) && ! is_null($disziplin)) {

        // $perfModified = ($disziplin->isTime()) ? TimeUtils::time2seconds($_POST[dbPerformance::PERFORMANCE]) : $_POST[dbPerformance::PERFORMANCE];

        // $minValueOk = $perfModified >= $disziplin->getMinValue();
        // $maxValueOk = $perfModified <= $disziplin->getMaxValue();
        // $teamTypeMatches = $athlete->getTeamType()->getId() == $disziplin->getTeamType()->getId();
        // $forcedEntry = (isset($_POST['forced'])) ? $_POST['forced'] == "true" : FALSE;

        // if (($minValueOk && $maxValueOk && $teamTypeMatches) || ($forcedEntry)) {
        if (DBInputUtils::validPerformanceForInput($performance) || $forcedEntry) {

            if (! DBInputUtils::performanceExistsInDb($db, $performance)) {

                if (DBInputUtils::performanceIsFromTVUBuchAndExists($db, $performance)) {
                    $result = new QuerryOutcome("The entered Performance does identically exist allready in A normal competition and not from The TVu Buch", false);
                } else {
                    $result = $db->add->performance($performance);
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
    return $result;
}

?>