    <?php
    use config\dbAthleteActiveYear;
    use config\dbAthletes;
    use config\dbCompetition;
    use config\dbCompetitionLocations;
    use config\dbCompetitionNames;
    use config\dbPerformance;
    use tvustat\AthleteNameOnly;
    use tvustat\Competition;
    use tvustat\CompetitionLocation;
    use tvustat\CompetitionName;
    use tvustat\DBMaintainer;
    use tvustat\DateFormatUtils;
    use tvustat\DisziplinNameOnly;
    use config\dbUnsureBirthDates;
    use tvustat\LoadByValues;
    use tvustat\QuerryOutcome;

    require_once '../vendor/autoload.php';

    $disziplin_exists = ($_POST['type'] == 'disziplinExists') ? TRUE : FALSE;
    $athlete_exists = ($_POST['type'] == 'athleteExists') ? TRUE : FALSE;
    $athlete_and_year_exists = ($_POST['type'] == 'athleteYearExists') ? TRUE : FALSE;
    $competition_exists = ($_POST['type'] == 'competitionExists') ? TRUE : FALSE;
    $performancesDisAthComp = ($_POST['type'] == 'performancesDisAthComp') ? TRUE : FALSE;
    $performancesDisAthYear = ($_POST['type'] == 'performancesDisAthYear') ? TRUE : FALSE;

    $competitionsInYear = ($_POST['type'] == 'competitionsForYears') ? TRUE : FALSE;
    $athletesforCategory = ($_POST['type'] == 'athletesforCategory') ? TRUE : FALSE;
    $similarAthletes = ($_POST['type'] == "similarAthlete") ? TRUE : FALSE;

    $allCompetitions = ($_POST['type'] == 'allCompetitions') ? TRUE : FALSE;
    $allCompetitionNames = ($_POST['type'] == 'allCompetitionNames') ? TRUE : FALSE;
    $allCompetitionLocations = ($_POST['type'] == 'allCompetitionLocations') ? TRUE : FALSE;
    $allAgeCategories = ($_POST['type'] == 'allAgeCategories') ? TRUE : FALSE;
    $allCategories = ($_POST['type'] == 'allCategories') ? TRUE : FALSE;
    $allOutputCategories = ($_POST['type'] == 'allOutputCategories') ? TRUE : FALSE;
    $allDisziplins = ($_POST['type'] == 'allDisziplins') ? TRUE : FALSE;
    $allAthletes = ($_POST['type'] == 'allAthletes') ? TRUE : FALSE;
    $allYears = ($_POST['type'] == 'allYears') ? TRUE : FALSE;
    $allSources = ($_POST['type'] == 'allSources') ? TRUE : FALSE;
    $allpointSchemeNames = ($_POST['type'] == 'allPointSchemeNames') ? TRUE : FALSE;

    $db = new DBMaintainer();

    if ($disziplin_exists) {
        $disziplinExists = $db->checkDisziplinExists(new DisziplinNameOnly($_POST["disziplin"], $db->getConn()));
        $converted_res = ($disziplinExists) ? 'true' : 'false';
        $result = array(
            "disziplinExists" => $converted_res,
            "disziplinName" => $_POST["disziplin"]
        );
        echo json_encode($result);
    }

    // CHECKS IF THE ATHLETE NAME EXIXSTS. THE DATE IS NOOOOT USED :( use checkAthlete
    if ($athlete_exists) {
        $date = DateTime::createFromFormat("d.m.Y", $_POST["date"]);
        $athleteExists = $db->checkAthleteExists(new AthleteNameOnly($_POST["fullName"], $db->getConn()));
        $converted_res = ($athleteExists) ? 'true' : 'false';

        $result = array(
            "athleteExists" => $converted_res,
            "fullName" => $_POST["fullName"],
            "date" => DateFormatUtils::formatDateForDB($date)
        );
        echo json_encode($result);
    }
    if ($athlete_and_year_exists) {
        $year = intval($_POST["year"]);
        $fullName = strval($_POST["fullName"]);
        $athlete = $db->loadbyValues->loadAthleteByName($fullName);
        $athleteIsInDb = FALSE;
        if (! is_null($athlete)) {
            $birthYearDb = DateFormatUtils::formatDateaAsYear($athlete->getDate());
            if ($birthYearDb != NULL) {
                if ($birthYearDb == $year && $athlete->getFullName() == $fullName) {
                    $result = new QuerryOutcome("Athlete Exists", TRUE);
                    echo json_encode($result->getJSONArray());
                    $athleteIsInDb = TRUE;
                }
            }
        }
        if (! $athleteIsInDb) {
            $result = new QuerryOutcome("Athlete Exists", FALSE);
            echo json_encode($result->getJSONArray());
        }
    }

    if ($competition_exists) {
        $date = DateTime::createFromFormat("d.m.Y", $_POST[dbCompetition::DATE]);
        $name = new CompetitionName($_POST[dbCompetitionNames::NAME]);
        $location = new CompetitionLocation($_POST[dbCompetitionLocations::VILLAGE], "");
        $competiton = new Competition($name, $location, $date);

        $competitionExists = $db->checkCompetitionExists($competiton);
        $converted_res = ($competitionExists) ? 'true' : 'false';

        $result = array(
            "competitionExists" => $converted_res,
            dbCompetitionNames::NAME => $name->getCompetitionName(),
            dbCompetition::DATE => DateFormatUtils::formatDateForDB($date),
            dbCompetitionLocations::VILLAGE => $location->getVillage()
            // "date" => $_POST["date"]
        );
        echo json_encode($result);
    }

    if ($performancesDisAthComp) {
        $results = array();
        foreach ($_POST[dbPerformance::DISZIPLINID] as $dbStoreId => $diszipliId) {
            $sql = "SELECT * FROM " . dbPerformance::DBNAME . " WHERE " . dbPerformance::ATHLETEID . " = " . $_POST[dbPerformance::ATHLETEID];
            // $sql .= " INNER JOIN " . dbDisziplin::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::DISZIPLINID . " = " . dbDisziplin::DBNAME . "." . dbDisziplin::ID;
            $sql .= " AND " . dbPerformance::COMPETITOINID . " = " . $_POST[dbPerformance::COMPETITOINID];
            $sql .= " AND " . dbPerformance::DISZIPLINID . " = " . $diszipliId;
            $r = $db->getConn()->executeSqlToArray($sql);
            if (sizeof($r) > 0) {
                $results[$dbStoreId] = $r;
            }
        }
        echo json_encode($results);
    }
    if ($performancesDisAthYear) {
        $results = array();
        foreach ($_POST[dbPerformance::DISZIPLINID] as $dbStoreId => $diszipliId) {
            $r = $db->loadPerformanceAthleteYear($diszipliId, $_POST[dbPerformance::ATHLETEID], $_POST["year"]);
            if (sizeof($r) > 0) {
                $results[$dbStoreId] = $r;
            }
        }
        echo json_encode($results);
    }

    if ($competitionsInYear) {

        echo json_encode($db->getCompetitionsForYear($_POST["years"]));
    }

    if ($allCompetitions) {
        echo json_encode($db->getAllCompetitions());
    }

    if ($allCompetitionNames) {
        echo json_encode($db->getAllCompetitionNames());
    }
    if ($allCompetitionLocations) {
        echo json_encode($db->getAllCompetitionLocations());
    }

    if ($allAgeCategories) {
        echo json_encode($db->getAllAgeCategories());
    }
    if ($allCategories) {
        echo json_encode($db->getAllCategories());
    }
    if ($allOutputCategories) {
        echo json_encode($db->getAllOutputCategories());
    }

    if ($allDisziplins) {
        echo json_encode($db->getAllDisziplins());
    }

    if ($allAthletes) {
        echo json_encode($db->getAllAthletes());
    }

    if ($allYears) {
        echo json_encode($db->getAllYears());
    }
    if ($allSources) {
        echo json_encode($db->getAllSources());
    }

    if ($allpointSchemeNames) {
        echo json_encode($db->getAllPointNameSchemes());
    }

    if ($athletesforCategory) {
        $year = $_POST["year"];

        $sql = "SELECT * FROM " . dbAthletes::DBNAME;
        $sql .= " LEFT JOIN " . dbAthleteActiveYear::DBNAME . " ON " . dbAthleteActiveYear::DBNAME . "." . dbAthleteActiveYear::ID . " = " . dbAthletes::DBNAME . "." . dbAthletes::ID;
        $sql .= " LEFT JOIN " . dbUnsureBirthDates::DBNAME . " ON " . dbUnsureBirthDates::DBNAME . "." . dbUnsureBirthDates::ID . " = " . dbAthletes::DBNAME . "." . dbAthletes::ID;

        $sql .= " WHERE (";
        $first = true;
        foreach ($_POST["categories"] as $id) {
            if (! $first) {
                $sql .= " OR";
            }
            $category = $db->getConn()->getCategory($id);
            $sql .= " (" . $year . " - EXTRACT(YEAR FROM " . dbAthletes::DBNAME . "." . dbAthletes::DATE . ") >= " . $category->getAgeCategory()->getMinAge() . " AND";
            $sql .= " " . $year . " - EXTRACT(YEAR FROM " . dbAthletes::DBNAME . "." . dbAthletes::DATE . ") <= " . $category->getAgeCategory()->getMaxAge() . " AND ";
            $sql .= dbAthletes::GENDERID . " = " . $category->getGender()->getId() . ")";
            $first = false;
        }
        $sql .= ") OR (" . dbAthletes::CATEGORY . " IN (" . implode(",", $_POST["categories"]) . ") ) ORDER BY " . dbAthletes::TEAMTYPEID . "," . dbAthletes::FULLNAME;
        echo json_encode($db->getConn()->executeSqlToArray($sql));
    }

    if ($similarAthletes) {
        $namepart = $_POST["athleteName"];

        $sql = "SELECT * FROM " . dbAthletes::DBNAME;
        $sql .= " LEFT JOIN " . dbAthleteActiveYear::DBNAME . " ON " . dbAthleteActiveYear::DBNAME . "." . dbAthleteActiveYear::ID . " = " . dbAthletes::DBNAME . "." . dbAthletes::ID;
        $sql .= " LEFT JOIN " . dbUnsureBirthDates::DBNAME . " ON " . dbUnsureBirthDates::DBNAME . "." . dbUnsureBirthDates::ID . " = " . dbAthletes::DBNAME . "." . dbAthletes::ID;

        $sql .= " WHERE " . dbAthletes::FULLNAME . " LIKE '%" . $namepart . "%' ORDER BY " . dbAthletes::FULLNAME;
        // echo $sql;
        echo json_encode($db->getConn()->executeSqlToArray($sql));
    }
    ?>
