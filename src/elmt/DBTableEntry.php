<?php
namespace tvustat;

abstract class DBTableEntry
{

    private $id;

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param int $id
     */
    protected function setId(int $id)
    {
        $this->id = $id;
    }
}

