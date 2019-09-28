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
            dbAthletes::FIRSTNAME,
            dbAthletes::LASTNAME
            // dbAthletes::DATE
        ));
    }

    /**
     *
     * @param CompetitionLocation $competitionLocation
     * @return boolean
     */
    public function competitionLocation(CompetitionLocation $competitionLocation)
    {
        return $this->check($this->getTable(dbCompetitionLocations::class), $competitionLocation, array(
            dbCompetitionLocations::VILLAGE,
            dbCompetitionLocations::FACILITY
        ));
    }

    /**
     *
     * @param CompetitionName $competitionName
     * @return boolean
     */
    public function competitionName(CompetitionName $competitionName)
    {
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
        return ($r != NULL) ? dbDisziplin::disziplinFromAsocArray($r[0], $this->conn): NULL;
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