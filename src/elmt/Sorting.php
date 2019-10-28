<?php
namespace tvustat;

class Sorting extends DBTableEntry
{

    /**
     *
     * @var string
     */
    private $sortingDirection;

    /**
     *
     * @var string
     */
    private $sortingDirectionSQL;

    public function __construct(string $sortingDirection, string $sortingDirectionSQL, int $id)
    {
        $this->sortingDirection = $sortingDirection;
        $this->sortingDirectionSQL = $sortingDirectionSQL;
        $this->setId($id);
    }

    /**
     *
     * @return string
     */
    public function getSortingDirection()
    {
        return $this->sortingDirection;
    }

    /**
     *
     * @return string
     */
    public function getSortingDirectionSQL()
    {
        return $this->sortingDirectionSQL;
    }

    public function sortASC()
    {
        return $this->sortingDirectionSQL == "ASC";
    }

    // /**
    // * @param string $sortingDirection
    // */
    // public function setSortingDirection($sortingDirection)
    // {
    // $this->sortingDirection = $sortingDirection;
    // }

    // /**
    // * @param string $sortingDirectionSQL
    // */
    // public function setSortingDirectionSQL($sortingDirectionSQL)
    // {
    // $this->sortingDirectionSQL = $sortingDirectionSQL;
    // }
}

