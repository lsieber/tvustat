<?php
namespace conn;

class connection
{

    protected $conn;
    
    public function __construct()
    {
        // Create connection
        $connection = new \mysqli(ConnectionParameters::SERVERNAME, ConnectionParameters::USERNAME, ConnectionParameters::PASSWORD, ConnectionParameters::DATABASE);
        $this->conn = $connection;
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

    protected function executeSqlToArray(string $sql)
    {
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     *
     * @return mixed
     */
    public function getConn()
    {
        return $this->conn;
    }

    /**
     *
     * @param mixed $conn
     */
    public function setConn($conn)
    {
        $this->conn = $conn;
    }
}
?>