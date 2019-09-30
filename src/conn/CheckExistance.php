<?php
namespace tvustat;

use config\dbAthletes;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbDisziplin;
use config\dbTableDescription;

class CheckExistance extends DbHandler
{

    /**
     *
     * @param Athlete $athlete
     * @return bool
     */
    public function athlete(Athlete $athlete)
    {
        return $this->check($this->getTable(dbAthletes::class), $athlete, array(
            dbAthletes::FULLNAME
            // dbAthletes::DATE
        ));
    }

    /**
     * If the entered competitionName has an ID it is checked if this id exists.
     * If no ID exist it is checked if the combination of lcation and facility exists in the Db
     *
     * @param CompetitionLocation $competitionLocation
     * @return boolean
     */
    public function competitionLocation(CompetitionLocation $competitionLocation)
    {
        if ($competitionLocation->getId() != NULL) {
            return $this->check($this->getTable(dbCompetitionLocations::class), $competitionLocation, array(
                dbCompetitionLocations::ID
            ));
        }
        return $this->check($this->getTable(dbCompetitionLocations::class), $competitionLocation, array(
            dbCompetitionLocations::VILLAGE,
            dbCompetitionLocations::FACILITY
        ));
    }

    /**
     * If the entered competitionName has an ID it is checked if this id exists.
     * If no ID exist it is checked if the name exists in the Db
     *
     * @param CompetitionName $competitionName
     * @return boolean
     */
    public function competitionName(CompetitionName $competitionName)
    {
        if ($competitionName->getId() != NULL) {
            return $this->check($this->getTable(dbCompetitionNames::class), $competitionName, array(
                dbCompetitionNames::ID
            ));
        }
        return $this->check($this->getTable(dbCompetitionNames::class), $competitionName, array(
            dbCompetitionNames::NAME
        ));
    }

    /**
     *
     * @param Competition $competition
     * @return boolean
     */
    public function competition(Competition $competition)
    {
        return $this->check($this->getTable(dbCompetition::class), $competition, array(
            dbCompetition::DATE,
            dbCompetition::LOCATIONID,
            dbCompetition::NAMEID
        ));
    }

    /**
     *
     * @param Disziplin $disziplin
     * @return boolean
     */
    public function disziplin(Disziplin $disziplin)
    {
        return $this->check($this->getTable(dbDisziplin::class), $disziplin, array(
            dbDisziplin::NAME
        ));
    }

    /**
     *
     * @param Disziplin $disziplin
     * @return boolean
     */
    public function loadDisziplin(Disziplin $disziplin)
    {
        $r = $this->load($this->getTable(dbDisziplin::class), $disziplin, array(
            dbDisziplin::NAME
        ));
        return ($r != NULL) ? dbDisziplin::disziplinFromAsocArray($r[0], $this->conn) : NULL;
    }

    /**
     *
     * @param dbTableDescription $desc
     * @param DBTableEntry $element
     * @param array $identifiers
     * @return boolean
     */
    private function check(dbTableDescription $desc, DBTableEntry $element, array $identifiers)
    {
        $r = $this->load($desc, $element, $identifiers);
        if ($r == NULL) {
            return FALSE;
        }
        return true;
    }

    /**
     *
     * @param dbTableDescription $desc
     * @param DBTableEntry $element
     * @param array $identifiers
     * @return boolean
     */
    private function load(dbTableDescription $desc, DBTableEntry $element, array $identifiers)
    {
        $k = $desc->getCollumNames();
        $v = $desc->classToCollumns($element);
        $table = $desc->getTableName();

        $sql = "SELECT * FROM " . $table . " WHERE ";

        $isFirst = TRUE;
        foreach ($identifiers as $i) {
            if (! $isFirst) {
                $sql .= " AND ";
            }
            $sql .= $i . "='" . $v[$k[$i]] . "'";
            $isFirst = FALSE;
        }
        $result = $this->conn->getConn()->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}