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
     * @return string
     */
    public function person(Athlete $athlete)
    {
        if (! PersonUtils::checkAthleteReadyForInsertion($athlete))
            return "Person " . $athlete->getName() . " needs more details for the DB";
        return ($this->check->athlete($athlete)) ? "Value Already exists" : $this->addElement($athlete, $this->getTable(dbAthletes::class));
    }

    /**
     * 
     * @param Disziplin $disziplin
     * @return string
     */
    public function disziplin(Disziplin $disziplin)
    {
        if (! DisziplinUtils::checkDisziplinReadyForInsertion($disziplin))
            return "Disziplin " . $disziplin->getName() . " needs more details for the DB";
        return ($this->check->disziplin($disziplin)) ? "Value Already exists" : $this->addElement($disziplin, $this->getTable(dbDisziplin::class));
    }

    /**
     *
     * @param Competition $competition
     * @return string
     */
    public function competition(Competition $competition)
    {
        if (! CompetitionUtils::checkCompetitionReadyForInsertion($competition)) {
            return "Competition needs more details to insert into the db";
        }
        if (! $this->check->competitionLocation($competition->getLocation())) {
            return "The Competition Location does Not Exist in the Database";
        }

        if (! $this->check->competitionName($competition->getName())) {
            return "The Competition Name does Not Exist in the Database";
        }
        return ($this->check->competition($competition)) ? "Value Already exists" : $this->addElement($competition, $this->getTable(dbCompetition::class));
    }

    public function competitionLocation(CompetitionLocation $location)
    {
        if (! CompetitionUtils::checkLocationReadyForInsertion($location)) {
            return "Competition needs more details to insert into the db";
        }
        return ($this->check->competitionLocation($location)) ? "Value Already exists" : $this->addElement($location, $this->getTable(dbCompetitionLocations::class));
    }

    public function competitionName(CompetitionName $name)
    {
        if (! CompetitionUtils::checkNameReadyForInsertion($name)) {
            return "Competition needs more details to insert into the db";
        }
        return ($this->check->competitionName($name)) ? "Value Already exists" : $this->addElement($name, $this->getTable(dbCompetitionNames::class));
    }

    private function addElement(DBTableEntry $element, dbTableDescription $desc)
    {
        $v = $desc->classToCollumns($element);

        $sql = "INSERT INTO " . $desc->getTableName() . " VALUES ('Null";
        for ($i = 1; $i < sizeof($v); $i ++) {
            $sql .= "','" . $v[$i];
        }
        $sql .= "')";

//         echo $sql;
        $result = $this->conn->getConn()->query($sql);
        $new_id = $this->conn->getConn()->insert_id;
        return ($result == 1) ? "Eingabe erfolgreich " . $v[1] . " wurde hinzugefügt, New ID: " . $new_id : "Eingabe von " . $v[1] . "nicht gelungen";
    }
}
