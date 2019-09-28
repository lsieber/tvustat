<?php
namespace tvustat;

class SwissAthleticsExportLoader extends PerformanceLoader
{

    private $disziplins = array();

    const FILEPATH = "../data/bltest.csv";

    private $dbMaintainer;

    public function __construct(DBMaintainer $dbMaintainer)
    {
        $this->dbMaintainer = $dbMaintainer;
    }

    public function readFile(string $filePath)
    {
        $prevLine = NULL;
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
                if ($data[0] == "Nr") {
                    $this->getDisziplin($prevLine[0]);
                }
                $prevLine = $data;
            }
            fclose($handle);
        }
    }

    private function getDisziplin($disziplinName)
    {
        $disziplinNameOnly = new DisziplinNameOnly($disziplinName, $this->dbMaintainer->getConn());
        if ($this->dbMaintainer->checkDisziplinExists($disziplinNameOnly)) {
            echo "Disziplin Exists" . $disziplinName;
            return $this->dbMaintainer->loadDisziplin($disziplinNameOnly);
        } else {
            echo "Disziplin does Not Exist" . $disziplinName;
        }
    }

    private function askUser($disziplinName) {
        
    }
    
    public function getData()
    {}
}

