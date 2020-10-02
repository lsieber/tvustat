<?php
namespace tvustat;

use config\dbPerformance;

class Delete extends DbHandler
{

    function performance(int $id)
    {
        $sql = "DELETE FROM " . dbPerformance::DBNAME . " WHERE " . dbPerformance::ID . " = " . $id;
        return $this->conn->getConn()->query($sql);
    }
}