<?php
namespace tvustat;

class TxtGenerator implements BestListOutputGenerator
{

    private const NEWLINE = "\n";

    private const SEP = "\t ";

    private $title;
    
    public function __construct(BestListTitle $title)
    {
        $this->title = $title;
    }

    public function createOutput(BestList $bestList, int $top = NULL)
    {
        $this->createTXT($bestList, $top);
    }
    
    private function createTXT(BestList $bestList, int $top = NULL)
    {
        $title = $this->title->getTxtFileTitle();
        $path = $this->createTXTFilePath($title);
        $result = $this->writeFile($bestList, $path, $title, $top);
        echo ($result . ", path: " . $path);
    }

    private function writeFile(BestList $bestList, $path, $title, int $top = NULL)
    {
        $myfile = fopen($path, "w") or die("Unable to open file!");
        fwrite($myfile, "\r\n");
        fwrite($myfile, utf8_decode($title) . "\t\t" . "Erstellt am: " . date("d.m.Y") . "\r\n");

        foreach ($bestList->getDisziplinBestLists() as $DisziplinBestList) {
            fwrite($myfile, self::NEWLINE);
            fwrite($myfile, utf8_decode($DisziplinBestList->getDisziplin()->getName() . self::NEWLINE));
            foreach ($DisziplinBestList->getTopList($top) as $performance) {
                // Change Information in Best List .TXT HERE!!!
                $a = implode(self::SEP, array(
                    $performance->getAthlete()->getFullName(),
                    $performance->getFormatedPerformance()
                ));
                fwrite($myfile, utf8_decode($a . self::NEWLINE));
            }
        }
        fclose($myfile);
        return "Success: Text File Created!";
    }

    private function printBestLIst($top = NULL)
    {
        foreach ($this->bestList as $disBestList) {
            $disBestList->printBestList($top);
            echo "<br>";
        }
    }

    private function createTXTFilePath(string $title, $category = NULL, $year = NULL)
    {
        $stufe = array();
        $path = "";
        // Create A TXT File for the usage in other programs (tab deliminated)
        if (! is_null($category) && ! is_null($year)) {
            $stufe[0] = "../data/output/Bestenlisten/einJahreineKategorie";
            $stufe[1] = implode("/", [
                $stufe[0],
                $year
            ]);
            $stufe[2] = implode("/", [
                $stufe[1],
                $category
            ]);
            if (! is_dir($stufe[2])) {
                if (! is_dir($stufe[1])) {
                    if (! is_dir($stufe[0])) {
                        mkdir($stufe[0]);
                    }
                    mkdir($stufe[1]);
                }
                mkdir($stufe[2]);
            }
            $path = $stufe[2] . "/" . $title . ".txt";
        } else {
            $path = "../data/output/Bestenlisten/andere/" . $title . ".txt";
        }
        return $path;
    }
}

