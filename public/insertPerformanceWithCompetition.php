<?php
require_once '../vendor/autoload.php';

use tvustat\Competition;
use tvustat\CompetitionLocation;
use tvustat\CompetitionName;
use tvustat\DBMaintainer;
use tvustat\Performance;
use tvustat\DateFormatUtils;
use tvustat\QuerryOutcome;

/**
 * ******************************************************************************************
 * THIS script inserts a given Performance from the Post variable into the DB
 * The Post Variable has to include the following Variables the keys can be found in the variable declaration below:
 * (string Athlete Name
 * int Athlete Birth Year)
 *  or (string Athlete saId) where if the saId is given and the athlete is found with the id this athlete is chosen
 * string Competition Name
 * string (yyyy-mm-dd) Competition Date
 * string Competition Location
 * string Disziplin Name
 * double Performance
 * double¦NULL Wind
 * string|NULL Ranking
 * string|NULL Detail
 *
 * If the Disziplin cannot be found as it is not in the database the Script will break.
 * The return value for this case is "NO_DISZIPLIN"
 *
 * If the Athlete cannot be found as it is not in the database the Script will break. The check is carried out based on the name and birth year.
 * The return value for this case is "NO_ATHLETE"
 *
 * If the Competition based on the values Competition Date, competition Name, competition Location cannot be found a new Competition is created.
 * the same is true for the Competition Name or the Competition Location
 *
 * If the Disziplin and the Athlete exist and the Competition exists or is created then the Performance is inserted into the DB.
 * If the Performance already exists the script will break and return "PERFORMANCE_EXISTS"
 * If the performance was inserted, the return value is "INSERTATION_SUCCESSFULL"
 * ***************************************************************************************
 */
//var_dump($_POST);
const STATUS = "STATUS";

const NO_DISZIPLIN_RETURN = "NO_DISZIPLIN";

const NO_ATHLETE_RETURN = "NO_ATHLETE";

const PERFORMANCE_EXISTS_RETURN = "PERFORMANCE_EXISTS";

const SUCCESSFULL_INSERTATION_RETURN = "INSERTATION_SUCCESSFULL";

/**
 * THE FOLLOWING VARIABLES DEFINE THE KEYS TO USE IN THE $_POST Variable
 */
const KEY_ATHLETE_LICENCE = "athleteLicense";

const KEY_ATHLETE_SAID = "athleteSaId";

const KEY_ATHLETE_NAME = "athleteName";

const KEY_ATHLETE_YEAR = "athleteYear";

const KEY_COMPETITION_NAME = "competitionName";

const KEY_COMPETITION_LOCATION = "competitionLocation";

const KEY_COMPETITION_Date = "competitionDate";

const KEY_DISZIPLIN = "disziplin";

const KEY_PERFORMANCE = "performance";

const KEY_WIND = "wind";

const KEY_RANKING = "ranking";

const KEY_DETAIL = "detail";

const KEY_SOURCE = "source";

/**
 * Check that the POST Variables are fine to use and have the correct format
 */
$licenseExists = array_key_exists(KEY_ATHLETE_LICENCE, $_POST);
$athleteLicense = $licenseExists ? assertInt(intval($_POST[KEY_ATHLETE_LICENCE]), "Error The Value of the Athlete License Number Year has to be of type int", TRUE) : NULL;
$saIdExists = array_key_exists(KEY_ATHLETE_SAID, $_POST);
$athleteSaId = $saIdExists ? assertString(strval($_POST[KEY_ATHLETE_SAID]), "Error The Value of the Athlete Swiss Athletics ID has to be of type string", TRUE) : NULL;
$athleteName = $licenseExists ? NULL : assertString($_POST[KEY_ATHLETE_NAME], "Error The Value of the Athlete Name has to be of type string", $licenseExists);
$athleteYear = $licenseExists ? NULL : assertInt(intval($_POST[KEY_ATHLETE_YEAR]), "Error The Value of the Athlete Birth Year has to be of type int", $licenseExists);
$cName = assertString($_POST[KEY_COMPETITION_NAME], "Error The Value of the Competition Name has to be of type string");
$village = assertString($_POST[KEY_COMPETITION_LOCATION], "Error The Value of the Competition Location has to be of type string");
$cDate = assertDate($_POST[KEY_COMPETITION_Date], "Error The Value of the Competition Date has to be of type string with format yyyy-mm-dd");
$disziplinName = assertString($_POST[KEY_DISZIPLIN], "Error The Value of the Disziplin has to be of type string");
$perf = assertDouble(doubleval($_POST[KEY_PERFORMANCE]), "Error The Value of the Athlete Birth Year has to be of type int");
$postwind = isset($_POST[KEY_WIND]) && $_POST[KEY_WIND] != "" ? doubleval($_POST[KEY_WIND]) : NULL;
$wind = assertDouble($postwind, "Error The Value of the Athlete Birth Year has to be of type int", true);
$postranking = isset($_POST[KEY_RANKING]) ? $_POST[KEY_RANKING] : NULL;
$ranking = assertString($postranking, "Error The Value of the Ranking has to be of type string", true);
$postdetail = isset($_POST[KEY_DETAIL]) && $_POST[KEY_DETAIL] != "" ? strval($_POST[KEY_DETAIL]) : NULL;
$detail = assertString($postdetail, "Error The Value of the Detail has to be of type string", true);
$postSource = isset($_POST[KEY_SOURCE]) && $_POST[KEY_SOURCE] != "" ? intval($_POST[KEY_SOURCE]) : NULL;
$sourceID = assertInt($postSource, "Source has to be an int");

/**
 * LOAD DB
 */
$db = new DBMaintainer();
$querry = new QuerryOutcome("Nothing done yet", false);

/**
 * Disziplin
 */
$disziplin = $db->getbyValues->disziplin($disziplinName);
if (is_null($disziplin)) {
    $querry->putCustomValue("message", "Disziplin " . $disziplinName . " nicht gefunden");
    $querry->putCustomValue(STATUS, NO_DISZIPLIN_RETURN);
} else {

    /**
     * Athlete
     */
    $athlete = null;
    if ($saIdExists) {
        $athlete = $db->getbyValues->athleteBySaId($athleteSaId);
    } elseif ($licenseExists){
        $athlete = $db->getbyValues->athleteBySaLicense($athleteLicense);
    } else {
        $athlete = $db->getbyValues->athlete($athleteName);
    }
    if (is_null($athlete)) {
        $athlete = $db->getbyValues->athlete($athleteName);
        if (is_null($athlete)){
            $querry->putCustomValue("message", "Athlete " . $athleteName . " nicht gefunden");
            $querry->putCustomValue(STATUS, NO_ATHLETE_RETURN);
        } else {
            if ($saIdExists) {
                $db->add->saIdToAthlete($athlete, $athleteSaId);
            }
            if ($licenseExists) {
                $db->add->licenseToAthlete($athlete, $licenseNumber);
            }
        }
    } 
    
    if (!is_null($athlete)) {
        /**
         * Competition Name
         */
        $competitionName = $db->getbyValues->competitionName($cName);
        if ($competitionName == NULL) {
            $competitionNameNew = new CompetitionName($cName);
            $db->add->competitionName($competitionNameNew);
            $competitionName = $db->getbyValues->competitionName($cName);
            $querry->putCustomValue("competitionName", "created new competition Name");
        }
        assert_NotNull($competitionName, "Competition Name " . $cName . " nicht gefunden");

        /**
         * Competition Location
         */
        $competitionLocation = $db->getbyValues->competitionLocation($village);
        if ($competitionLocation == NULL) {
            $competitionLocationNew = new CompetitionLocation($village, NULL);
            $db->add->competitionLocation($competitionLocationNew);
            $competitionLocation = $db->getbyValues->competitionLocation($village);
            $querry->putCustomValue("competitionLocation", "created new competition Location");
        }
        assert_NotNull($competitionLocation, "Competition Location " . $village . " nicht gefunden");

        /**
         * Competition
         */
        $competition = $db->getbyValues->competition($cName, $village, $cDate);
        if ($competition == NULL) {
            $competitionNew = new Competition($competitionName, $competitionLocation, $cDate);
            $db->add->competition($competitionNew);
            $competition = $db->getbyValues->competition($cName, $village, $cDate);
            $querry->putCustomValue("competition", "created new competition");
        }
        assert_NotNull($competition != NULL, "Competition " . $cName . $village . DateFormatUtils::formatDateForBL($cDate) . " nicht gefunden");

        /**
         * Performance
         */
        $source = $db->getConn()->getSource($sourceID);
        $preformance = new Performance($disziplin, $athlete, $competition, $perf, $wind, $ranking, FALSE, $source, NULL, $detail);

        $querryInsertation = $db->add->performance($preformance);
        $querry->putCustomValue("message", $querryInsertation->getMessage());
        $querryInsertation->getSuccess() ? $querry->putCustomValue(STATUS, SUCCESSFULL_INSERTATION_RETURN) : $querry->putCustomValue(STATUS, PERFORMANCE_EXISTS_RETURN);
        $querry->putCustomValue("performance", $preformance->getPerformance());
        $querry->putCustomValue("insertationJSOn", json_encode($querryInsertation->getJSONArray()));
    }
}

echo json_encode($querry->getJSONArray());

/**
 * ****************************************************
 */
/**
 * HELPER FUNCTIONS *
 */
/**
 * Could be moved to another file
 */
/**
 * ****************************************************
 */

/**
 *
 * @param unknown $potentialString
 * @param string $msg
 * @param boolean $nullable
 * @return NULL|string
 */
function assertString($potentialString = NULL, $msg, $nullable = FALSE)
{
    return assertVariableType($potentialString, $msg, "string", $nullable);
}

/**
 *
 * @param unknown $potentialInt
 * @param string $msg
 * @param boolean $nullable
 * @return NULL|int
 */
function assertInt($potentialInt = NULL, $msg, $nullable = FALSE)
{
    return assertVariableType($potentialInt, $msg, "integer", $nullable);
}

/**
 *
 * @param unknown $potentialDouble
 * @param string $msg
 * @param boolean $nullable
 * @return NULL|double
 */
function assertDouble($potentialDouble = NULL, $msg, $nullable = FALSE)
{
    return assertVariableType($potentialDouble, $msg, "double", $nullable);
}

/**
 *
 * @param unknown $potentialDate
 * @param string $msg
 * @param boolean $nullable
 * @return NULL|DateTime
 */
function assertDate($potentialDate = NULL, $msg, $nullable = FALSE)
{
    assertVariableType($potentialDate, $msg, "string", $nullable);
    assert(strlen($potentialDate) == 10);
    assert(substr($potentialDate, 4, 1) == "-");
    assert(substr($potentialDate, 7, 1) == "-");
    return DateFormatUtils::DateTimeFromDB($potentialDate);
}

/**
 *
 * @param unknown $potentialString
 * @param string $msg
 * @param boolean $nullable
 * @param
 *            string vartype
 */
function assertVariableType($potentialVar = NULL, $msg, $vartype, $nullable = FALSE)
{
    if (! $nullable) {
        assert_NotNull($potentialVar, $msg);
    } else {
        if (is_null($potentialVar)) {
            return NULL;
        }
    }
    if (gettype($potentialVar) != $vartype) {
        echo $msg;
    }
    assert(gettype($potentialVar) == $vartype);
    return $potentialVar;
}

function assert_NotNull($obj = NULL, $msg)
{
    if (is_null($obj)) {
        echo $msg . "and its NULL";
    }
    assert(! is_null($obj));
    return $obj;
}
