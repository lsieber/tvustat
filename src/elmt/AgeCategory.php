<?php
namespace tvustat;

class AgeCategory extends DBTableEntry
{

    private $name;

    private $oldname;

    private $minAge;

    private $maxAge;

    public function __construct(string $name, string $oldname, int $minAge, int $maxAge, int $id)
    {
        $this->name = $name;
        $this->oldname = $oldname;
        $this->minAge = $minAge;
        $this->maxAge = $maxAge;
        $this->setId($id);
    }

    // ***********
    // GETTERS
    // ***********

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
     * @return string
     */
    public function getOldName()
    {
        return $this->oldname;
    }

    /**
     *
     * @return int
     */
    public function getMinAge()
    {
        return $this->minAge;
    }

    /**
     *
     * @return int
     */
    public function getMaxAge()
    {
        return $this->maxAge;
    }
}

