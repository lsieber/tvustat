<?php
namespace test;

use tvustat\ConnectionPreloaded;

class TeamTypeTest
{

    private $connection;

    private function __construct()
    {
        $this->connection = new ConnectionPreloaded();
    }

    private function testGetDisziplinType()
    {
        for ($i = 1; $i < 10; $i ++) {
            $this->connection->getDisziplinType($i);
        }
    }

    private function printAllDisziplinTypes()
    {
        foreach ($this->connection->getAllDisziplinTypes() as $value) {
            echo $value->getId() . ", " . $value->getType() . "\t\n";
        }
    }

    public static function run()
    {
        $test = new TeamTypeTest();
        $test->testGetDisziplinType();
        $test->printAllDisziplinTypes();
    }
}

