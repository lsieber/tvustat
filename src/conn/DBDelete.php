<?php

class DBDelete extends Connection
{

    public function performance($id)
    {
        $this->deleteIdFromTable(DBSettings::BL, $id);
    }

    public function wettkampf($id)
    {
        $this->deleteIdFromTable(DBSettings::COMP, $id);
    }

    public function disziplin($id)
    {
        $this->deleteIdFromTable(DBSettings::DIS, $id);
    }

    public function person($id)
    {
        $this->deleteIdFromTable(DBSettings::PERS, $id);
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

