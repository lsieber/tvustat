<?php
namespace tvustat;

class Competition extends DBTableEntry
{

    // Mandatory Fields
    /**
     *
     * @var int
     */
    private $nameID;

    /**
     *
     * @var int
     */
    private $locatoinID;

    /**
     *
     * @var \DateTime
     */
    private $date;

    public function __construct(int $nameID, int $locatoinID, \DateTime $date, string $id = NULL)
    {
        $this->nameID = $nameID;
        $this->date = $date;
        $this->locatoinID = $locatoinID;
        if ($id != NULL)
            $this->setId($id);
    }

    /**
     *
     * @return string
     */
    public function getYear()
    {
        return DateFormatUtils::formatDateaAsYear($this->date);
    }

    /**
     *
     * @return string
     */
    public function getFormatedDate()
    {
        return DateFormatUtils::formatDateForBL($this->date);
    }

    // *********************
    // GETTERS AND SETTERS
    // *********************
    /**
     *
     * @return string
     */
    public function getNameID()
    {
        return $this->nameID;
    }

    /**
     *
     * @return string
     */
    public function getLocationID()
    {
        return $this->locatoinID;
    }

    /**
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    // /**
    // *
    // * @param string $name
    // */
    // public function setName($name)
    // {
    // $this->name = $name;
    // }

    // /**
    // *
    // * @param string $place
    // */
    // public function setPlace($place)
    // {
    // $this->place = $place;
    // }

    // /**
    // *
    // * @param mixed $date
    // */
    // public function setDate($date)
    // {
    // $this->date = $date;
    // }

    // /**
    // *
    // * @param string $dateFormat
    // */
    // public function setDateFormat($dateFormat)
    // {
    // $this->dateFormat = $dateFormat;
    // }
}

