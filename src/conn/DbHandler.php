<?php
namespace tvustat;

use config\dbConfig;

abstract class DbHandler
{

    /**
     *
     * @var ConnectionPreloaded
     */
    protected $conn;

    /**
     *
     * @var dbConfig
     */
    protected $config;

    function __construct(ConnectionPreloaded $conn, dbConfig $config)
    {
        $this->conn = $conn;
        $this->config = $config;
    }

    protected function getTable(string $className)
    {
        return $this->config->getTableDesc($className);
    }
}

