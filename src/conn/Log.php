<?php
namespace tvustat;

use config\dbConfig;

class Log extends DbHandler
{

    function __construct(ConnectionPreloaded $conn, dbConfig $config)
    {
        parent::__construct($conn, $config);
    }

    function bestList()
    {
        $ip = getUserIpAddr();
        $sql = "INSERT INTO `logbestlist`(`id`, `ip`,`header`, `postValue`, `date`) VALUES ('NULL','" . $ip . "','" . serialize(getallheaders()) . "','" . serialize($_POST) . "','" . DateFormatUtils::nowForDB() . "') ";
//         echo $sql;
        return $this->conn->getConn()->query($sql);
    }
}

function getUserIpAddr()
{
    if (! empty($_SERVER['HTTP_CLIENT_IP'])) {
        // ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}