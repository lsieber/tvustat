<?php
namespace tvustat;

class TxtGeneratorAsString implements BestListOutputGenerator
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
        $title = $this->title->getTxtFileTitle();
        return new StringOutput($this->createTxtString($bestList, $title, $top));
    }

    private function createTxtString(BestList $bestList, $title, int $top = NULL)
    {
        // Title
        $s = $title . "\t\t" . "Erstellt am: " . date("d.m.Y") . "\r\n";
        foreach ($bestList->getDisziplinBestLists() as $DisziplinBestList) {
            $s .= self::NEWLINE;
            $s .= $DisziplinBestList->getDisziplin()->getName() . self::NEWLINE;
            foreach ($DisziplinBestList->getTopList($top) as $performance) {
                // Change Information in Best List .TXT HERE!!!
                $a = implode(self::SEP, array(
                    $performance->getAthlete()->getFullName(),
                    $performance->getFormatedPerformance()
                ));
                $s.=  $a . self::NEWLINE;
            }
        }
        return $s;
    }

}

