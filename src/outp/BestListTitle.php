<?php
namespace tvustat;

class BestListTitle
{

    const typeBestList = "Bestenliste";

    const typeRecord = "Rekord";

    // Input values
    private $category;

    private $gender;

    private $year;

    private $top;

    private $disziplin;

    // Title String
    private $title;

    private $titleParts;

    public function __construct(array $category, Gender $gender, array $year, $top, $disziplin)
    {
        $this->category = $category;
        $this->gender = $gender;
        $this->year = $year;
        $this->top = $top;
        $this->disziplin = $disziplin;
    }

    public function createTitle()
    {
        $this->titleParts = array();
        array_push($this->titleParts, ($this->top == 1) ? self::typeRecord : self::typeBestList);

        array_push($this->titleParts, $this->getKatGenderString());
        array_push($this->titleParts, $this->getYearString());
        if ($this->getTopString() != "") {
            array_push($this->titleParts, $this->getTopString());
        }
        $this->title = implode(", ", $this->titleParts);
    }

    public function getTxtFileTitle()
    {
        $this->createTitle();
        return implode("_", $this->titleParts);
    }

    private function getKatGenderString()
    {
        $sex = $this->gender->getName(); // TODO use German version
        
        $kat = "";
        foreach ($this->category as $key => $one_kat) {
            $kat .= ($key == 0) ? "" : ", ";
            $kat .= $one_kat->getName();
//             if ($one_kat == Categories::ADULTKEYSTRING) {
//                 // TODO clean this up not hard coded
//                 switch ($this->gender->getNumericalValue()) {
//                     case 3:
//                         $kat = "Frauen und Männer";
//                         break;
//                     case 1:
//                         $kat = "Frauen";
//                         break;
//                     case 2:
//                         $kat = "Männer";
//                         break;
//                     default:
//                         echo "Keine Kategorie gewaehlt";
//                 }
//                 $sex = "";
//             } else {
//                 $kat .= $one_kat;
//             }
//         }
//         if ($this->category[0] == Categories::ALL) {
//             $kat = "Alle Kategorien ";
//         }

        }
        return $kat . $sex;
    }

    private function getYearString()
    {
        if ($this->year[0] == "all") {
            return "Alle Jahre";
        }
        sort($this->year);
        return implode(" & ", $this->year);
    }

    private function getTopString()
    {
        $top = "";

        switch ($this->top) {
            case '1001':
                $top = "";
                break;
            case 'record':
                $top = "";
                break;
            case '1':
                $top = "";
                break;
            default:
                $top = "Top " . $this->top;
                break;
        }
        return $top;
    }

    /**
     *
     * @return array
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     *
     * @return Gender
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     *
     * @return array
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     *
     * @return mixed
     */
    public function getTop()
    {
        return $this->top;
    }

    /**
     *
     * @return array
     */
    public function getDisziplin()
    {
        return $this->disziplin;
    }

    /**
     *
     * @return string
     */
    public function getTitle()
    {
        $this->createTitle();
        return $this->title;
    }

    /**
     *
     * @param array $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     *
     * @param Gender $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     *
     * @param array $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     *
     * @param mixed $top
     */
    public function setTop($top)
    {
        $this->top = $top;
    }

    /**
     *
     * @param array $disziplin
     */
    public function setDisziplin($disziplin)
    {
        $this->disziplin = $disziplin;
    }
}

