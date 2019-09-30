<?php
namespace tvustat;

use config\dbAthletes;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbConfig;
use config\dbTableDescription;
use config\dbDisziplin;

class AddElement extends DbHandler
{

    private $check;

    function __construct(ConnectionPreloaded $conn, dbConfig $config)
    {
        parent::__construct($conn, $config);
        $this->check = new CheckExistance($conn, $config);
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
        return ($this->check->competitionLocation($location)) ? new QuerryOutcome("Value Already exists", false) : $this->addElement($location, $this->getTable(dbCompetitionLocations::class));
    }

    public function competitionName(CompetitionName $name)
    {
        if (! CompetitionUtils::checkNameReadyForInsertion($name)) {
            return new QuerryOutcome("Competition needs more details to insert into the db", false);
        }
        return ($this->check->competitionName($name)) ? new QuerryOutcome("Value Already exists", false) : $this->addElement($name, $this->getTable(dbCompetitionNames::class));
    }

    private function addElement(DBTableEntry $element, dbTableDescription $desc)
    {
        $v = $desc->classToCollumns($element);

        $sql = "INSERT INTO " . $desc->getTableName() . " VALUES ('Null";
        for ($i = 1; $i < sizeof($v); $i ++) {
            $sql .= "','" . $v[$i];
        }
        $sql .= "')";

        // echo $sql;
        $result = $this->conn->getConn()->query($sql);
        $new_id = $this->conn->getConn()->insert_id;
        $success = ($result == 1);
        $message =  $success? "Eingabe erfolgreich " . $v[1] . " wurde hinzugefügt, New ID: " . $new_id : "Eingabe von " . $v[1] . "nicht gelungen";
        return new QuerryOutcome($message, $success);
    }
}
