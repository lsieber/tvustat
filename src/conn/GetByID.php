<?php
namespace tvustat;

use config\dbAthletes;
use config\dbTableDescription;
use config\dbDisziplin;

class GetByID extends DbHandler
{

    const select = "SELECT * FROM ";

    public function athlete(int $id)
    {
        $r = self::getQuerryResult($this->getTable(dbAthletes::class), $id);
        return ($r == NULL) ? NULL : $this->personFromAsocArray($r);
    }


    

    
    private function copetitionFromAsocArray($r)
    {
        return new Competition( //
            $r[dbAthletes::FULLNAME], //
            $r[dbAthletes::LASTNAME], //
            new \DateTime($r[dbAthletes::DATE]), //
            $this->conn->getGender($r[dbAthletes::GENDERID]), //
            $this->conn);
    }

    private function getQuerryResult(dbTableDescription $desc, int $id)
    {
        $idString = $desc->getIDString();
        $table = $desc->getTableName();

        $sql = self::select . $table . " WHERE " . $idString . "=" . $id;

        $r = $this->conn->executeSqlToArray($sql);

        if (sizeof($r) != 1) {
            if (sizeof($r) > 1) {
                echo "Multiple Elements hit";
            }
            return NUll;
        }
        return $r[0];
    }
}

