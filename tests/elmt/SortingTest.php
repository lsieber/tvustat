<?php
namespace test;

use tvustat\ConnectionPreloaded;

class SortingTest
{

    private $connection;

    private function __construct()
    {
        $this->connection = new ConnectionPreloaded();
    }

    private function testGetSorting()
    {
        for ($i = 1; $i < 10; $i ++) {
            $sorting = $this->connection->getSorting($i);
            if ($sorting != null) {
                echo $sorting->getId() . ", " . $sorting->getSortingDirection() . ", " . $sorting->getSortingDirectionSQL() . "\t\n";
            }
        }
    }

    public static function run()
    {
        $test = new SortingTest();
        $test->testGetSorting();
    }
}

