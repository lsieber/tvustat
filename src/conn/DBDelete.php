<?php
namespace tvustat;

use config\dbCompetition;
use config\dbDisziplin;
use config\dbAthletes;

class DBDelete extends DbHandler
{

//     public function performance($id)
//     {
//         $this->deleteIdFromTable(, $id);
//     }

    public function competition($id)
    {
        $this->deleteIdFromTable(dbCompetition::ID, $id);
    }

    public function disziplin($id)
    {
        $this->deleteIdFromTable(dbDisziplin::ID, $id);
    }

    public function athlete($id)
    {
        $this->deleteIdFromTable(dbAthletes::ID, $id);
    }

    private function deleteIdFromTable(string $table, $id)
    {
        $sql_check_existance = "SELECT * FROM " . $table . " WHERE ID='" . $id . "'";
        $result_check = $this->conn->query($sql_check_existance);
        if ($result_check->fetch_all(MYSQLI_ASSOC) != NULL) {
            $sql = "DELETE FROM " . $table . " WHERE ID = " . $id . ";";
            $result = $this->conn->query($sql);
            ($result == 1) ? print_r('ID: ' . $id . ' removed from ' . $table . '</br></br>') : print_r("removement failed!!!</br></br>");
        }
    }
}

