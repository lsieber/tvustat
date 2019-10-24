// <?php
// use config\dbAthletes;
// use config\dbCompetition;
// use config\dbPerformance;
// use tvustat\DBMaintainer;
// use config\dbAthleteActiveYear;

// require_once '../vendor/autoload.php';
// $db = new DBMaintainer();

// $athetes = $r = $db->getConn()->executeSqlToArray("SELECT * From " . dbAthletes::DBNAME . " ORDER BY " . dbAthletes::FULLNAME);

// foreach ($athetes as $athlete) {
//     $athleteID = $athlete[dbAthletes::ID];
//     $sql = "SELECT MAX(EXTRACT(YEAR FROM " . dbCompetition::DATE . ")) AS maxYear FROM " . dbPerformance::DBNAME;
//     $sql .= " INNER JOIN " . dbCompetition::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::COMPETITOINID . " = " . dbCompetition::DBNAME . "." . dbCompetition::ID;
//     // $sql .= " INNER JOIN " . dbAthletes::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::ATHLETEID . " = " . dbAthletes::DBNAME . "." . dbAthletes::ID;
//     $sql .= " WHERE " . dbPerformance::ATHLETEID . " = " . $athleteID;
//     $maxYear = intval($db->getConn()->executeSqlToArray($sql)[0]["maxYear"]);

//     // echo $athleteID;
//     // echo $athlete[dbAthletes::FULLNAME];
//     // echo $maxYear;
//     echo "</br> AthleteID: " . strval($athleteID) . " Athlete: " . strval($athlete[dbAthletes::FULLNAME]) . " Max Year : " . strval($maxYear);
//     $activeYear = intval($maxYear) + 3;
//     if($maxYear == 2019){
//         $activeYear = 2024;
//     }
//     if ($activeYear == 0) {
//         $activeYear = 2019;
//     }
//     $sqlActive = "INSERT INTO " . dbAthleteActiveYear::DBNAME . " VALUES (" . $athleteID . "," . $activeYear . ")";
    
//     echo $sqlActive;
//     $db->getConn()->getConn()->query($sqlActive);
// }

// ?>