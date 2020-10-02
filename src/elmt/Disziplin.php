<?php
namespace tvustat;

class Disziplin extends DBTableEntry
{

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var Sorting
     */
    private $sorting;

    /**
     *
     * @var float
     */
    private $orderNumber;

    /**
     *
     * @var bool
     */
    private $isTime;

    /**
     *
     * @var bool
     */
    private $isDecimal;

    /**
     *
     * @var DisziplinType
     */
    private $disziplinType;

    /**
     *
     * @var TeamType
     */
    private $teamType;

    /**
     *
     * @var float
     */
    private $minValue;

    /**
     *
     * @var float
     */
    private $maxValue;

    // private $sql_val_kat;

    // private $pointsSLV2010IDWoman;

    // private $pointsSLV2010IDMan;
    // private $associatedCombinedEventIds;
    public function __construct(string $name, Sorting $sorting, float $orderNumber, bool $isTime, bool $isDecimal, DisziplinType $disziplinType, TeamType $teamType, float $minValue, float $maxValue, int $id = NULL)
    {
        $this->name = $name;
        $this->sorting = $sorting;
        $this->orderNumber = $orderNumber;
        $this->isTime = $isTime;
        $this->isDecimal = $isDecimal;
        $this->disziplinType = $disziplinType;
        $this->teamType = $teamType;
        $this->minValue = $minValue;
        $this->maxValue = $maxValue;
        // $this->sql_val_kat = $sql_val_kat;
        // $this->laufsort = ($laufsort == NULL) ? (int) strlen($name) : $laufsort;
        if ($id != NULL)
            $this->setId($id);
    }

    // ********************
    // GETTERS
    // ********************

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return \tvustat\Sorting
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     *
     * @return number
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     *
     * @return boolean
     */
    public function isTime()
    {
        return $this->isTime;
    }

    /**
     *
     * @return boolean
     */
    public function isDecimal()
    {
        return $this->isDecimal;
    }

    /**
     *
     * @return \tvustat\DisziplinType
     */
    public function getDisziplinType()
    {
        return $this->disziplinType;
    }

    /**
     *
     * @return \tvustat\TeamType
     */
    public function getTeamType()
    {
        return $this->teamType;
    }

    /**
     *
     * @return number
     */
    public function getMinValue()
    {
        return $this->minValue;
    }

    /**
     *
     * @return number
     */
    public function getMaxValue()
    {
        return $this->maxValue;
    }

    /**
     *
     * @param Disziplin $otherDisziplin
     * @return boolean
     */
    public function equals(Disziplin $otherDisziplin)
    {
        if ($otherDisziplin == NULL)
            return FALSE;

        return $otherDisziplin->getName() == $this->getName() && $otherDisziplin->getId() == $this->getId();
    }
}

