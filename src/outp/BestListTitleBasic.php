<?php
namespace tvustat;

use config\CategoryControl;

class BestListTitleBasic implements BestListTitle
{

    const typeBestList = "Bestenliste";

    const typeRecord = "Rekord";

    // Input values
    private $categoryControl;

    private $category;

    private $year;

    private $top;

    private $disziplin;

    // Title String
    private $title;

    private $titleParts;

    public function __construct(string $categoryControl, array $category, array $year, $top, $disziplin)
    {
        $this->categoryControl = $categoryControl;
        $this->category = $category;
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
//         if ($this->getTopString() != "") {
//             array_push($this->titleParts, $this->getTopString());
//         }
        $this->title = implode(", ", $this->titleParts);
    }

    public function getTxtFileTitle()
    {
        $this->createTitle();
        return implode("_", $this->titleParts);
    }

    private function getKatGenderString()
    {
        $kat = "";
        switch ($this->categoryControl) {
            case CategoryControl::ALL:
                $kat = "Alle Kategorien";
                break;
            case CategoryControl::MEN:
                $kat = "Alle Männer";
                break;
            case CategoryControl::WOMEN:
                $kat = "Alle Frauen";
                break;
            default:
                foreach ($this->category as $key => $one_kat) {
                    $kat .= ($key == 0) ? "" : " & ";
                    $kat .= $one_kat->getName();
                }
                break;
        }
        return $kat;
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

