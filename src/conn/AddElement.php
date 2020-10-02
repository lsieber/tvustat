<?php
namespace tvustat;

use config\dbAthleteActiveYear;
use config\dbAthletes;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbConfig;
use config\dbDisziplin;
use config\dbPerformance;
use config\dbPerformanceDetail;
use config\dbTableDescription;
use config\dbUnsureBirthDates;

class AddElement extends DbHandler
{

    /**
     *
     * @var GetByID
     */
    private $getById;

    /**
     *
     * @var LoadByValues
     */
    private $getByValue;

    /**
     *
     * @var Delete
     */
    private $delete;

    function __construct(ConnectionPreloaded $conn, dbConfig $config)
    {
        parent::__construct($conn, $config);
        $this->getById = new GetByID($conn, $config);
        $this->getByValue = new LoadByValues($conn, $config);
        $this->delete = new Delete($conn, $config);
    }

    // public function performanceWithIdsOnly($arrayAssociative)
    // {
    // $values = array();
    // $arrayAssociative[dbPerformance::LASTCHANGE] = DateFormatUtils::nowForDB();
    // $arrayAssociative[dbPerformance::PERFORMANCE] = TimeUtils::time2seconds($arrayAssociative[dbPerformance::PERFORMANCE]);
    // $arrayAssociative[dbPerformance::MANUALTIME] = self::stringTruefalseToTrueFalse($arrayAssociative[dbPerformance::MANUALTIME]);
    // $collumns = dbPerformance::getCollumNames();
    // foreach ($collumns as $key => $dbPosition) {
    // $values[$dbPosition] = isset($arrayAssociative[$key]) ? $arrayAssociative[$key] : NULL;
    // }
    // $querry = $this->addValues($values, $this->getTable(dbPerformance::class));

    // if (array_key_exists(dbPerformanceDetail::DETAIL, $arrayAssociative)) {
    // $detail = $arrayAssociative[dbPerformanceDetail::DETAIL];
    // if ($detail != NULL && $detail != "") {
    // $perfId = $querry->getCustomValue(dbPerformance::getIDString());
    // $sqlDetail = "INSERT INTO " . dbPerformanceDetail::DBNAME . ' VALUES (Null, ' . $perfId . ',"' . $detail . '")';
    // $result = $this->conn->getConn()->query($sqlDetail);
    // $querry->putCustomValue("DetailInsertion", ($result == 1));
    // }
    // }
    // return $querry;
    // }

    // private function stringTruefalseToTrueFalse(string $stringBoolean)
    // {
    // if ($stringBoolean == "true")
    // return TRUE;
    // elseif ($stringBoolean == "false")
    // return FALSE;
    // else
    // return null;
    // }

    /**
     *
     * @param Performance $performance
     * @return QuerryOutcome
     */
    public function performance(Performance $performance)
    {
        $athleteDb = $this->getById->athlete($performance->getAthlete()
            ->getId());
        if (! $performance->getAthlete()->equals($athleteDb)) {
            return new QuerryOutcome("The Athlete " . $performance->getAthlete()->getFullName() . " could not be found in the Database", False);
        }

        $disziplinDb = $this->getById->disziplin($performance->getDisziplin()
            ->getId());
        if (! $performance->getDisziplin()->equals($disziplinDb)) {
            return new QuerryOutcome("The Disziplin " . $performance->getDisziplin()->getName() . " could not be found in the Database", False);
        }

        $competitionDb = $this->getById->competition($performance->getCompetition()
            ->getId());
        if (! $performance->getCompetition()->equals($competitionDb)) {
            return new QuerryOutcome("The Competition " . $performance->getCompetition()
                ->getName()
                ->getCompetitionName() . " could not be found in the Database", False);
        }

        $perfExists = $this->getByValue->performanceElmt($performance);
        if (!is_null($perfExists)) {
            if (is_null($perfExists->getDetail()) && !is_null($performance->getDetail())) {
                $result = $this->performanceDetail($perfExists->getId(), $performance->getDetail());
                return new QuerryOutcome("Performance Already exists. But the detail was added", FALSE);
            }
            return new QuerryOutcome("Performance Already exists.", FALSE);
        }

        if (DBInputUtils::validPerformanceForInput($performance)) {

            $columns = dbPerformance::classToCollumns($performance);

            $querry = $this->addValues($columns, $this->getTable(dbPerformance::class));
            $newPerfId = $querry->getCustomValue(dbPerformance::getIDString());
            if (! is_null($performance->getDetail())) {
                $result = $this->performanceDetail($newPerfId, $performance->getDetail());
                $querry->putCustomValue("DetailInsertion", ($result == 1));
            }
            
            $newPerf = $this->getById->performance($newPerfId);
            $querry->putCustomValue(dbAthletes::FULLNAME, $newPerf->getAthlete()
                ->getFullName());
            $querry->putCustomValue(dbDisziplin::NAME, $newPerf->getDisziplin()
                ->getName());
            $querry->putCustomValue(dbPerformance::PERFORMANCE, $newPerf->getPerformance());

            if ($querry->getSuccess()) {
                // DELETE THE PERFORMANCE IF IT WAS ALREADY IN THE DB FROM THE TVU BUCH
                $disziplinID = $newPerf->getDisziplin()->getId();
                $athleteID = $newPerf->getAthlete()->getId();
                $year = DateFormatUtils::formatDateaAsYear($newPerf->getCompetition()->getDate());
                foreach ($this->getByValue->performanceAthleteYear($disziplinID, $athleteID, $year) as $performanceRaw) {
                    if ($performanceRaw[dbPerformance::PERFORMANCE] == $newPerf->getPerformance()) {
                        $compExisting = $this->getById->competition($performanceRaw[dbPerformance::COMPETITOINID]);
                        if (CompetitionUtils::isFromTVUBuch($compExisting)) {
                            $this->delete->performance($performanceRaw[dbPerformance::ID]);
                            $querry->putCustomValue("REMOVED SAME TVU BUCH ENTRY", $performanceRaw[dbPerformance::PERFORMANCE]);
                        }
                    }
                }
            }
            return $querry;
        }
        return new QuerryOutcome("The entry of the Performance failed, the specifications are not met!", false);
    }

    /**
     *
     * @param int $performanceID
     * @param string $detail
     * @return boolean
     */
    public function performanceDetail(int $performanceID, string $detail)
    {
        $sqlDetail = "INSERT INTO " . dbPerformanceDetail::DBNAME . ' VALUES (Null, ' . $performanceID . ',"' . $detail . '")';
        return $this->conn->getConn()->query($sqlDetail);
    }

    /**
     *
     * @param Athlete $athlete
     * @return QuerryOutcome
     */
    public function athlete(Athlete $athlete)
    {
        if (! AthleteUtils::checkAthleteReadyForInsertion($athlete))
            return new QuerryOutcome("Person " . $athlete->getName() . " needs more details for the DB", false);
        return ($this->getByValue->athlete($athlete->getFullName()) != NULL) ? new QuerryOutcome("Value Already exists", false) : $this->addElement($athlete, $this->getTable(dbAthletes::class));
    }

    /**
     *
     * @param Disziplin $disziplin
     * @return string
     */
    public function disziplin(Disziplin $disziplin)
    {
        if (! DisziplinUtils::checkDisziplinReadyForInsertion($disziplin))
            return new QuerryOutcome("Disziplin " . $disziplin->getName() . " needs more details for the DB", false);
        return ($this->getByValue->disziplin($disziplin) != NULL) ? new QuerryOutcome("Value Already exists", false) : $this->addElement($disziplin, $this->getTable(dbDisziplin::class));
    }

    /**
     *
     * @param Competition $competition
     * @return string
     */
    public function competition(Competition $competition)
    {
        if (! CompetitionUtils::checkCompetitionReadyForInsertion($competition)) {
            return new QuerryOutcome("Competition needs more details to insert into the db", false);
        }

        if ($this->getByValue->competitionLocation($competition->getLocation()->getVillage()) == NULL) {
            return new QuerryOutcome("The Competition Location does Not Exist in the Database", false);
        }

        if ($this->getByValue->competitionName($competition->getName()->getCompetitionName()) == NULL) {
            return new QuerryOutcome("The Competition Name does Not Exist in the Database", false);
        }
        return ($this->getByValue->competition($competition->getName()->getCompetitionName(), $competition->getLocation()->getVillage(), $competition->getDate()) != NULL) ? new QuerryOutcome("Value Already exists", false) : $this->addElement($competition, $this->getTable(dbCompetition::class));
    }

    public function competitionLocation(CompetitionLocation $location)
    {
        if (! CompetitionUtils::checkLocationReadyForInsertion($location)) {
            return new QuerryOutcome("Competition needs more details to insert into the db", false);
        }
        $querry = ($this->getByValue->competitionLocation($location->getVillage()) != NULL) ? new QuerryOutcome("Value Already exists", false) : $this->addElement($location, $this->getTable(dbCompetitionLocations::class));
        $querry->putCustomValue(dbCompetitionLocations::VILLAGE, $location->getVillage());
        $querry->putCustomValue(dbCompetitionLocations::FACILITY, $location->getFacility());
        return $querry;
    }

    /**
     *
     * @param CompetitionName $name
     * @return \tvustat\QuerryOutcome
     */
    public function competitionName(CompetitionName $name)
    {
        if (! CompetitionUtils::checkNameReadyForInsertion($name)) {
            return new QuerryOutcome("Competition needs more details to insert into the db", false);
        }
        $querry = (!is_null($this->getByValue->competitionName($name->getCompetitionName()))) ? new QuerryOutcome("Value Already exists", false) : $this->addElement($name, $this->getTable(dbCompetitionNames::class));
        $querry->putCustomValue(dbCompetitionNames::NAME, $name->getCompetitionName());
        return $querry;
    }

    public function athleteActiveYear(int $athleteID, int $athleteActiveYear)
    {
        $sqlActive = "INSERT INTO " . dbAthleteActiveYear::DBNAME . " VALUES (" . $athleteID . "," . $athleteActiveYear . ")";
        return $this->conn->getConn()->query($sqlActive);
    }

    public function unsureBirthDate(int $athleteId, bool $isUnsureDate, bool $isUnsureYear, int $minYear = null, int $maxYear = null)
    {
        $sqlUnsure = "INSERT INTO " . dbUnsureBirthDates::DBNAME . " VALUES (" . $athleteId . "," . intval($isUnsureDate) . "," . intval($isUnsureYear) . "," . self::nullToString($minYear) . "," . self::nullToString($maxYear) . ")";
        return $this->conn->getConn()->query($sqlUnsure);
    }

    private static function nullToString($nullableValue = null)
    {
        return ($nullableValue == null) ? "null" : $nullableValue;
    }

    public function saIdToAthlete(Athlete $athlete, string $saId)
    {
        $athleteId = $athlete->getId();
        $athleteDb = $this->getByValue->athlete($athlete->getFullName());
        if ($athleteId == $athleteDb->getId() && is_null($athleteDb->getSaId())) {
            $sqlUpdate = "UPDATE `athletes` SET " . dbAthletes::SAID . " = '" . $saId . "' WHERE athleteID = " . $athleteId;
            return $this->conn->getConn()->query($sqlUpdate);
        }
        return null;
    }

    public function licenseToAthlete(Athlete $athlete, string $license)
    {
        $athleteId = $athlete->getId();
        $athleteDb = $this->getByValue->athlete($athlete->getFullName());
        if ($athleteId == $athleteDb->getId() && is_null($athleteDb->getLicenseNumber())) {
            $sqlUpdate = "UPDATE `athletes` SET " . dbAthletes::lICENCE . " = " . $license . " WHERE athleteID = " . $athleteId;
            return $this->conn->getConn()->query($sqlUpdate);
        }
        return null;
    }

    /**
     *
     * @param DBTableEntry $element
     * @param dbTableDescription $desc
     * @return \tvustat\QuerryOutcome
     */
    private function addElement(DBTableEntry $element, dbTableDescription $desc)
    {
        $v = $desc->classToCollumns($element);

        return $this->addValues($v, $desc);
    }

    private function addValues(array $values, dbTableDescription $desc)
    {
        $sql = "INSERT INTO " . $desc->getTableName() . " VALUES (Null";
        for ($i = 1; $i < sizeof($values); $i ++) {
            $sql .= "," . self::sqlValue($values[$i]);
        }
        $sql .= ")";

        // echo $sql;
        $result = $this->conn->getConn()->query($sql);
        /**
         * OUTPUT
         */
        $new_id = $this->conn->getConn()->insert_id;
        $success = ($result == 1);
        $message = ($success) ? "Eingabe erfolgreich " /*. $v[1] . " has been inserted and the new ID is " . $new_id */: "Eingabe von " . $values[1] . " nicht gelungen";
        $querry = new QuerryOutcome($message, $success);
        $querry->putCustomValue($desc->getIDString(), $new_id);
        $querry->putCustomValue("sql", $sql);
        return $querry;
    }

    public static function sqlValue($v)
    {
        if (is_bool($v)) {
            return $v ? 1 : 0;
        }
        if (strval($v) == "") {
            return "NULL";
        }
        if (is_null($v)) {
            return "NULL";
        }
        if (is_string($v)) {
            return '"' . $v . '"';
        }
        return $v;
    }
}
