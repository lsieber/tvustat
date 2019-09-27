<?php
namespace test;

use tvustat\ConnectionPreloaded;

class DisziplinTypeTest
{

    private $connection;

    private function __construct()
    {
        $this->connection = new ConnectionPreloaded();
    }

    private function testGetTeamType()
    {
        for ($i = 1; $i < 10; $i ++) {
            $this->connection->getTeamType($i);
        }
    }

    private function printAllTeamTypes()
    {
        foreach ($this->connection->getAllTeamTypes() as $value) {
            echo $value->getId() . ", " . $value->getType() . "\t\n";
        }
    }

    public static function run()
    {
        $test = new DisziplinTypeTest();
        $test->testGetTeamType();
        $test->printAllTeamTypes();
    }
}

