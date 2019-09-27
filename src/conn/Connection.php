<?php
namespace tvustat;

use config\ConnectionParameters;

class Connection
{

    /**
     * 
     * @var \mysqli
     */
    protected $conn;
    
    public function __construct()
    {
        // Create connection
        $this->conn = new \mysqli(ConnectionParameters::SERVERNAME, ConnectionParameters::USERNAME, ConnectionParameters::PASSWORD, ConnectionParameters::DATABASE);
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (! $this->conn->set_charset("utf8")) {
            printf("Error loading character set utf8: %s\n", $conn->error);
            exit();
        }

        if ($this->conn == NULL) {
            echo "No Connection To server Possible";
        }
    }

    /**
     * 
     * @param string $sql
     * @return mixed
     */
    public function executeSqlToArray(string $sql)
    {
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     *
     * @return \mysqli
     */
    public function getConn()
    {
        return $this->conn;
    }

//     /**
//      *
//      * @param mixed $conn
//      */
//     public function setConn($conn)
//     {
//         $this->conn = $conn;
//     }
}
?>