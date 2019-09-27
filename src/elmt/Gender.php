<?php
namespace tvustat;

class Gender extends DBTableEntry
{

    private $name;

    private $shortName;

    public function __construct(string $name, string $shortname, int $id)
    {
        $this->name = $name;
        $this->shortName = $shortname;
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
    public function getShortName()
    {
        return $this->shortName;
    }
}

