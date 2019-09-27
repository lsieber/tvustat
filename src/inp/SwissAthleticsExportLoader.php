<?php
namespace tvustat;

class SwissAthleticsExportLoader extends PerformanceLoader
{

    const FILEPATH = "../data/bltest.csv";

    public function __construct()
    {}

    public function readFile(string $filePath)
    {
        $row = 1;
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
                $num = count($data);
//                 echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                for ($c=0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
            }
            fclose($handle);
        }
    }

    public function getData()
    {}
}

