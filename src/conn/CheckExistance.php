<?php
namespace tvustat;

use config\dbAthletes;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbDisziplin;
use config\dbTableDescription;
use config\dbPerformance;

class CheckExistance extends DbHandler
{

    public function checkAthleteIDExists(int $athleteId)
    {
        return $this->checkValues($this->getTable(dbAthletes::class), array(
            $athleteId
        ), array(
            dbAthletes::ID
        ));
    }

    public function checkCompetitionIDExists(int $competitionId)
    {
        return $this->checkValues($this->getTable(dbCompetition::class), array(
            $competitionId
        ), array(
            dbCompetition::ID
        ));
    }

    public function checkDisziplinIDExists(int $disziplinId)
    {
        return $this->checkValues($this->getTable(dbDisziplin::class), array(
            $disziplinId
        ), array(
            dbDisziplin::ID
        ));
    }

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

    public function performanceByIds(array $post)
    {
        $identifiers = array(
            dbPerformance::ATHLETEID,
            dbPerformance::DISZIPLINID,
            dbPerformance::COMPETITOINID,
            dbPerformance::PERFORMANCE,
            dbPerformance::WIND,
            dbPerformance::PLACE
        );
        $values = array();
        foreach ($identifiers as $id) {
            $values[dbPerformance::getCollumNames()[$id]] = $post[$id];
        }
        $values[dbPerformance::getCollumNames()[dbPerformance::PERFORMANCE]] = TimeUtils::time2seconds($post[dbPerformance::PERFORMANCE]); // THIS is required to make sure we have a value for inseretation where the wind is ""
        $values[dbPerformance::getCollumNames()[dbPerformance::WIND]] = WindUtils::wind2DB($post[dbPerformance::WIND]); // THIS is required to make sure we have a value for inseretation where the wind is ""
        return $this->checkValues($this->getTable(dbPerformance::class), $values, $identifiers);
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
        if ($competition->getId() != NULL) {
            return $this->check($this->getTable(dbCompetition::class), $competition, array(
                dbCompetition::ID
            ));
        }
        if (! $this->competitionName($competition->getName())) {
            return false;
        }

        $competitionName = ($competition->getName()->getId() == NULL) ? $this->loadCompetitionName($competition->getName()) : $competition->getName();

        if (! $this->competitionLocation($competition->getLocation())) {
            return false;
        }
        $competitionLocation = ($competition->getLocation()->getId() == NULL) ? $this->loadCompetitionLocation($competition->getLocation()) : $competition->getLocation();
        $updatedCompetition = new Competition($competitionName, $competitionLocation, $competition->getDate());

        return $this->check($this->getTable(dbCompetition::class), $updatedCompetition, array(
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
     * @return Disziplin
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
     * @param CompetitionName $compName
     * @return CompetitionName
     */
    public function loadCompetitionName(CompetitionName $compName)
    {
        $r = $this->load($this->getTable(dbCompetitionNames::class), $compName, array(
            dbCompetitionNames::NAME
        ));
        return ($r != NULL) ? dbCompetitionNames::competitionNameFromAsocArray($r[0], $this->conn) : NULL;
    }

    /**
     *
     * @param CompetitionLocation $compLoc
     * @return CompetitionLocation
     */
    public function loadCompetitionLocation(CompetitionLocation $compLoc)
    {
        $r = $this->load($this->getTable(dbCompetitionLocations::class), $compLoc, array(
            dbCompetitionLocations::VILLAGE
        ));
        return ($r != NULL) ? dbCompetitionLocations::competitionLocationFromAsocArray($r[0], $this->conn) : NULL;
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
        $values = $desc->classToCollumns($element);
        return $this->checkValues($desc, $values, $identifiers);
    }

    private function checkValues(dbTableDescription $desc, array $values, array $identifiers)
    {
        $k = $desc->getCollumNames();
        $table = $desc->getTableName();

        $sql = "SELECT COUNT(1) FROM " . $table . " WHERE ";

        $isFirst = TRUE;
        foreach ($identifiers as $i) {
            if (! $isFirst) {
                $sql .= " AND ";
            }
            $comp = $values[$k[$i]];
            if ($comp == NULL) {
                $sql .= $i . " IS NULL";
            } else {
                $sql .= $i . '="' . $comp . '"';
            }
            $isFirst = FALSE;
        }
        // echo $sql;
        $result = $this->conn->getConn()->query($sql);
        $r = $result->fetch_all(MYSQLI_ASSOC);
        if ($r[0]["COUNT(1)"] == 0) {
            return FALSE;
        }
        return TRUE;
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