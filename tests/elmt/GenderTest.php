<?php
namespace test;

use tvustat\ConnectionPreloaded;

class GenderTest
{

    private $connection;

    private function __construct()
    {
        $this->connection = new ConnectionPreloaded();
    }

    private function testGetGender()
    {
        for ($i = 1; $i < 10; $i ++) {
            $gender = $this->connection->getGender($i);
            if ($gender!=null) {
                echo $gender->getId() . ", " . $gender->getShortName() . ", " . $gender->getName()."\t\n";
            }
        }
    }

    public static function run()
    {
        $test = new GenderTest();
        $test->testGetGender();
    }
}

