// <?php
// use config\dbAthletes;
// use config\dbCompetition;
// use config\dbCompetitionLocations;
// use config\dbCompetitionNames;
// use config\dbDisziplin;
// use config\dbPerformance;
// use tvustat\Athlete;
// use tvustat\CompetitionLocation;
// use tvustat\CompetitionName;
// use tvustat\CompetitionOnlyIds;
// use tvustat\DBMaintainer;
// use tvustat\Disziplin;
// use tvustat\QuerryOutcome;
// use tvustat\TimeUtils;
// use config\dbAthleteActiveYear;
// use tvustat\DateFormatUtils;
// use tvustat\CompetitionUtils;
// use tvustat\Performance;
// use config\dbUnsureBirthDates;
// use tvustat\AthleteNameOnly;

// require_once '../vendor/autoload.php';

// $db = new DBMaintainer();
// $c = $db->getConn();

// $athleteId = array_key_exists(dbAthletes::ID, $_POST) ? $_POST[dbAthletes::ID] : NULL;

// $athleteExists = False;
// if ($athleteId == NULL) {
//     $athleteName = array_key_exists(dbAthletes::FULLNAME, $_POST) ? $_POST[dbAthletes::FULLNAME] : NULL;
//     $athleteDate = array_key_exists(dbAthletes::DATE, $_POST) ? DateTime::createFromFormat("m.d.Y", $_POST[dbAthletes::DATE]) : NULL;

//     if (is_null($athleteName) || is_null($athleteDate)) {
//         echo json_encode((new QuerryOutcome("Not sufficient Information", False))->getJSONArray());
//     } else {
//         $athlete = $db->loadbyValues->loadAthleteByName($athleteName);
//         if ($athlete == NULL) {
//             echo json_encode((new QuerryOutcome("Could not find Athlete with name " . $athleteName, False))->getJSONArray());
//         } elseif (DateFormatUtils::formatDateForDB($athlete->getDate()) != DateFormatUtils::formatDateForDB($athleteDate)) {
//             $result = new QuerryOutcome("Birth Date does not match", False);
//             $result->putCustomValue("birth Date DB:", DateFormatUtils::formatDateForBL($athlete->getDate()));
//             $result->putCustomValue("birth Date Input:", DateFormatUtils::formatDateForBL($athleteDate));
//             echo json_encode($result->getJSONArray());
//         } else {
//             $athleteId = $athlete->getId();
//             $athleteExists = True;
//         }
//     }
// }
// if ($athleteExists) {
//     $athlete = $db->getAthlete($athleteId);
//     if (is_null($athlete->getLicenseNumber()) || $athlete->getLicenseNumber() == 0 || $athlete->getLicenseNumber() == "") {
//         $licenceNumber = $_POST[dbAthletes::lICENCE];

//         $sqlAthlete = "UPDATE " . dbAthletes::DBNAME . " SET " . dbAthletes::lICENCE . "=" . $licenceNumber . " WHERE " . dbAthletes::ID . "=" . $athleteId . "";
//         $sqltest = "INSERT INTO athletes (athleteID, fullName, genderID, teamTypeID, date, licenceNumber, teamCategoryID) VALUES ('NULL','TEST',1,1,2020-10-10,12345678,'NULL')";

//         $sqlResultAthlete = $c->getConn()->query($sqlAthlete);
//         $result = new QuerryOutcome("The Athlete was changed:", $sqlResultAthlete);
//         $result->putCustomValue("SQL", $sqlAthlete);
//         if (! $sqlResultAthlete) {
//             $result->putCustomValue("errormessage", mysqli_error($c->getConn()));
//         }
//         echo json_encode($result->getJSONArray());
//     } else {
//         $result = new QuerryOutcome("The License Number for " . $athlete->getFullName() . " already exists. Its Value is " . $athlete->getLicenseNumber(), False);
//         echo json_encode($result->getJSONArray());
//     }
// }

// ?>

