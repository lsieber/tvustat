<?php

class DBOutput extends ConnectionExtension
{

    // SQL Strings for getting an Element By ID
    private $sqlDisziplinById = "SELECT * FROM disziplin WHERE ID=";

    private $sqlPersonById = "SELECT * FROM mitglied WHERE ID=";

    private $sqlCompetitionById = "SELECT * FROM wettkampf WHERE ID=";

    private $sqlPerformanceById = "SELECT *, b.ID AS " . ConnectionExtension::PERFORMANCEIDALIAS . " FROM Bestenliste b INNER JOIN disziplin d ON b.DisziplinID = d.ID INNER JOIN wettkampf w ON b.Wettkampf = w.ID INNER JOIN mitglied m ON b.Mitglied = m.ID WHERE b.Id=";

    // Other SQL Strings
    private $sqlYearsInDB = "SELECT DISTINCT Jahr FROM Bestenliste INNER JOIN wettkampf ON bestenliste.Wettkampf = wettkampf.ID ORDER BY Jahr DESC ";

    private $sqlDisziplinInDB = "SELECT DISTINCT d.ID, d.Disziplin FROM Bestenliste INNER JOIN disziplin d ON bestenliste.DisziplinID = d.ID INNER JOIN wettkampf w ON bestenliste.Wettkampf = w.ID INNER JOIN mitglied m ON bestenliste.Mitglied = m.ID";

    private $sqlVisibleCompetitions = "SELECT * FROM wettkampf WHERE sichtbar=1 ORDER BY Datum ASC";

    private $sqlDisziplinOrder = " ORDER BY Lauf ASC, Laufsort ASC, d.Disziplin ASC";

    private $sqlPerformancesForCompetition = "SELECT *, b.ID AS " . ConnectionExtension::PERFORMANCEIDALIAS . " FROM Bestenliste b INNER JOIN disziplin d ON b.DisziplinID = d.ID INNER JOIN wettkampf w ON b.Wettkampf = w.ID INNER JOIN mitglied m ON b.Mitglied = m.ID WHERE w.Id=";

    private $sqlPerformanceOrder = " ORDER BY b.ID DESC";

    private $sqlLastNEntries = "SELECT *, b.ID AS " . ConnectionExtension::PERFORMANCEIDALIAS . " FROM Bestenliste b INNER JOIN disziplin d ON b.DisziplinID = d.ID INNER JOIN mitglied m ON b.Mitglied = m.ID INNER JOIN wettkampf w ON w.ID = b.Wettkampf ORDER BY b.ID DESC LIMIT ";

    // GETETRS By Its ID
    public function getDisziplinById(string $id)
    {
        $disziplinDB = $this->executeSqlToArray($this->sqlDisziplinById . $id);
        return $this->getDisziplinFromTable($disziplinDB[0]);
    }

    public function getPersonByID($id)
    {
        $personDB = $this->executeSqlToArray($this->sqlPersonById . $id);
        return $this->getPersonFromTable($personDB[0]);
    }

    public function getCompetitionByID(string $id)
    {
        $competitionDB = $this->executeSqlToArray($this->sqlCompetitionById . $id);
        return $this->getCompetitionFromTable($competitionDB[0]);
    }

    public function getPerformanceByID(string $id)
    {
        $competitionDB = $this->executeSqlToArray($this->sqlPerformanceById . $id);
        return $this->getPerformanceOfBLArray($competitionDB[0], self::PERFORMANCEIDALIAS);
    }

    // OTHER FUNCTIONS
    // For Performances
    public function getPerformancesForCompetition(string $id)
    {
        $sql = $this->sqlPerformancesForCompetition . $id . " " . $this->sqlPerformanceOrder;
        $performancesDB = $this->executeSqlToArray($sql);
        return self::getPerformancesOfBLArray($performancesDB);
    }

    public function lastNentries(int $numberEntries)
    {
        $sqlLastEntries = $this->sqlLastNEntries . $numberEntries;
        $performancesDB = $this->executeSqlToArray($sqlLastEntries);
        return self::getPerformancesOfBLArray($performancesDB);
    }

    // For Competition
    public function getYearsInDb()
    {
        $years = array();
        $result = $this->conn->query($this->sqlYearsInDB);
        foreach ($result as $year) {
            array_push($years, $year["Jahr"]);
        }
        return $years;
    }

    function getVisibleCompetitions()
    {
        $sql_wettkampf = $this->sqlVisibleCompetitions;
        $competitionsDB = $this->executeSqlToArray($sql_wettkampf);
        return self::getCompetitionsFromTable($competitionsDB);
    }

    // For Disziplins
    public function getDisziplinsForCategory(string $category)
    {
        $sql = "SELECT * FROM disziplin WHERE " . $category . "=1 ORDER BY Lauf, Laufsort, Disziplin";
        $array_result = $this->executeSqlToArray($sql);
        return self::getDisziplinsFromTable($array_result);
    }

    public function getDisziplinInDb($years = null, $gender = null, $categories = null)
    {
        // TODO This seems not so nice. Improve it!
        $disziplins = array();
        $whereStatement = "";
        if ($years != null) { // || $gender != null || $categories != null
            $whereStatement = "WHERE";
            $allStatements = array();
            if ($years != null) {
                array_push($allStatements, "w.Jahr IN (" . implode(", ", $years) . ")");
            }
            $whereStatement .= " " . implode(" AND ", $allStatements);
        }
        $result = $this->conn->query($this->sqlDisziplinInDB . " " . $whereStatement . " " . $this->sqlDisziplinOrder);
        foreach ($result as $disziplin) {
            array_push($disziplins, $disziplin["Disziplin"]);
        }
        return $disziplins;
    }

    // For Persons
    public function getPersonsInDb(string $firstName = "", string $lastName = "", int $maxNumberOfResults = 10)
    {
        $sql = "SELECT * FROM `mitglied` WHERE Name LIKE '" . $lastName . "%' AND Vorname LIKE '" . $firstName . "%' ORDER BY Geschlecht, Vorname, Name LIMIT $maxNumberOfResults";
        $array_result = $this->executeSqlToArray($sql);
        return self::getPersonsFromTable($array_result);
    }

    public function getPersonsForKat(Category $category, int $year)
    {
        $sql = $category->getSQLforPerson($year);
        $array_result = $this->executeSqlToArray($sql);
        $persons = self::getPersonsFromTable($array_result);

        if (AgeCategories::isActiveCategory($category->getAgeCategory())) {
            foreach (AgeCategories::getActiveCategories() as $activeCategory) {
                $category->setAgeCategory($activeCategory);
                $persons = $this->addTeamsOfCategoryToPersons($category, $persons, $year);
            }
        } else {
            $persons = $this->addTeamsOfCategoryToPersons($category, $persons, $year);
        }
        return $persons;
    }

    private function addTeamsOfCategoryToPersons(Category $category, $persons, int $year)
    {
        $array_result = $this->executeSqlToArray($category->getSQLforTeam($year));
        $teams = self::getPersonsFromTable($array_result);
        foreach ($teams as $team) {
            array_push($persons, $team);
        }
        return $persons;
    }

    // Point Calculator
    function pointCalculator(array $disziplinIds, array $performances, string $personId)
    {
        $laufArray = array();
        $pointsArray = array();
        $punkteSLVIds = array();

        if (sizeof($disziplinIds) != sizeof($performances)) {
            echo "Number of disziplins and performances is not equal";
        }

        $person = $this->getPersonByID($personId);
        foreach ($disziplinIds as $index => $disziplinId) {
            $disziplin = $this->getDisziplinById($disziplinId);
            $punkteslv2010_id = ($person->getGender()->getNumericalValue() == 1) ? $disziplin->getPointsSLV2010IDWoman() : $disziplin->getPointsSLV2010IDMan();

            if ($punkteslv2010_id != "" && $punkteslv2010_id != 0 && $performances[$index] != "") {
                $sqlPointsById = "SELECT * FROM punkteslv2010 WHERE Id=" . $punkteslv2010_id;
                $result_parameter = $this->executeSqlToArray($sqlPointsById);
                $points = StaticFunctions::calculatePoints($result_parameter[0]["parameter_a"], $result_parameter[0]["parameter_b"], $result_parameter[0]["parameter_c"], $result_parameter[0]["LaufFormel"], floatval(TimeUtils::time2seconds($performances[$index])));

                $punkteSLVIds[$index] = $punkteslv2010_id;
                $pointsArray[$index] = $points;
            } else {
                $pointsArray[$index] = 0;
            }
            $laufArray[$index] = $disziplin->getLauf();
        }
        // Return Value for the Java Script to parse
        echo implode(",", $disziplinIds);
        echo "//";
        echo implode(",", $laufArray);
        echo "//";
        echo implode(",", $pointsArray);
        echo "//";
        echo implode(",", $punkteSLVIds);
    }
}