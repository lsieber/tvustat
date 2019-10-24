<?php
namespace tvustat;

use config\dbAthletes;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbConfig;
use config\dbDisziplin;
use config\dbPerformance;
use config\dbTableDescription;
use config\dbPerformanceDetail;

class AddElement extends DbHandler
{

    private $check;

    function __construct(ConnectionPreloaded $conn, dbConfig $config)
    {
        parent::__construct($conn, $config);
        $this->check = new CheckExistance($conn, $config);
    }

    public function performanceWithIdsOnly($arrayAssociative)
    {
        $values = array();
        $arrayAssociative[dbPerformance::LASTCHANGE] = DateFormatUtils::nowForDB();
        $arrayAssociative[dbPerformance::PERFORMANCE] = TimeUtils::time2seconds($arrayAssociative[dbPerformance::PERFORMANCE]);
        $collumns = dbPerformance::getCollumNames();
        foreach ($collumns as $key => $dbPosition) {
            $values[$dbPosition] = isset($arrayAssociative[$key]) ? $arrayAssociative[$key] : NULL;
        }
        $querry = $this->addValues($values, $this->getTable(dbPerformance::class));
        if (array_key_exists(dbPerformanceDetail::DETAIL, $arrayAssociative)) {
            $detail = $arrayAssociative[dbPerformanceDetail::DETAIL];
            if ($detail != NULL && $detail != "") {
                $perfId = $querry->getCustomValue(dbPerformance::getIDString());
                $sqlDetail = "INSERT INTO " . dbPerformanceDetail::DBNAME . ' VALUES (Null, ' . $perfId . ',"' . $detail . '")';
                $result = $this->conn->getConn()->query($sqlDetail);
                $querry->putCustomValue("DetailInsertion", ($result == 1));
            }
        }
        return $querry;
    }

    /**
     *
     * @param Performance $performance
     * @return QuerryOutcome
     */
    public function performance(Performance $performance)
    {
        $disziplin = $performance->getDisziplin();

        $perfModified = ($disziplin->isTime()) ? TimeUtils::time2seconds($performance->getPerformance()) : $performance->getPerformance();
        $minValueOk = $perfModified >= $disziplin->getMinValue();
        $maxValueOk = $perfModified <= $disziplin->getMaxValue();
        $teamTypeMatches = $performance->getAthlete()
            ->getTeamType()
            ->getId() == $disziplin->getTeamType()->getId();
        if (($minValueOk && $maxValueOk && $teamTypeMatches)) {
            $querry = $this->addElement($performance, $this->getTable(dbPerformance::class));
            // echo "Detail: " . $performance->getDetail();
            if (! is_null($performance->getDetail())) {
                $perfId = $querry->getCustomValue(dbPerformance::getIDString());
                $sqlDetail = "INSERT INTO " . dbPerformanceDetail::DBNAME . ' VALUES (Null, ' . $perfId . ',"' . $performance->getDetail() . '")';
                // echo $sqlDetail;
                $result = $this->conn->getConn()->query($sqlDetail);
                // var_dump($result);
                $querry->putCustomValue("DetailInsertion", ($result == 1));
            }
            return $querry;
        }
        return new QuerryOutcome("The entry of the Performance failed, the specifications are not met!", false);
    }

    /**
     *
     * @param Athlete $athlete
     * @return QuerryOutcome
     */
    public function person(Athlete $athlete)
    {
        if (! PersonUtils::checkAthleteReadyForInsertion($athlete))
            return new QuerryOutcome("Person " . $athlete->getName() . " needs more details for the DB", false);
        return ($this->check->athlete($athlete)) ? new QuerryOutcome("Value Already exists", false) : $this->addElement($athlete, $this->getTable(dbAthletes::class));
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
        return ($this->check->disziplin($disziplin)) ? new QuerryOutcome("Value Already exists", false) : $this->addElement($disziplin, $this->getTable(dbDisziplin::class));
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
        if (! $this->check->competitionLocation($competition->getLocation())) {
            return new QuerryOutcome("The Competition Location does Not Exist in the Database", false);
        }

        if (! $this->check->competitionName($competition->getName())) {
            return new QuerryOutcome("The Competition Name does Not Exist in the Database", false);
        }
        return ($this->check->competition($competition)) ? new QuerryOutcome("Value Already exists", false) : $this->addElement($competition, $this->getTable(dbCompetition::class));
    }

    public function competitionLocation(CompetitionLocation $location)
    {
        if (! CompetitionUtils::checkLocationReadyForInsertion($location)) {
            return new QuerryOutcome("Competition needs more details to insert into the db", false);
        }
        $querry = ($this->check->competitionLocation($location)) ? new QuerryOutcome("Value Already exists", false) : $this->addElement($location, $this->getTable(dbCompetitionLocations::class));
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
        $querry = ($this->check->competitionName($name)) ? new QuerryOutcome("Value Already exists", false) : $this->addElement($name, $this->getTable(dbCompetitionNames::class));
        $querry->putCustomValue(dbCompetitionNames::NAME, $name->getCompetitionName());
        return $querry;
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
        $new_id = $this->conn->getConn()->insert_id;
        $success = ($result == 1);
        $message = ($success) ? "Eingabe erfolgreich " /*. $v[1] . " wurde hinzugef�gt and the new ID is " . $new_id */: "Eingabe von " . $values[1] . " nicht gelungen";
        $querry = new QuerryOutcome($message, $success);
        $querry->putCustomValue($desc->getIDString(), $new_id);

        $querry->putCustomValue("sql", $sql);

        return $querry;
    }

    private static function sqlValue($v)
    {
        if (is_bool($v)) {
            return $v ? 1 : 0;
        }
        if ($v == "") {
            return "NULL";
        }
        if ($v == NULL) {
            return "NULL";
        }
        if (is_string($v)) {
            return '"' . $v . '"';
        }
        return $v;
    }
}
