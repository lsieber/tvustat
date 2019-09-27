<?php
namespace tvustat;

class TeamType extends DBTableEntry
{

    /**
     *
     * @var string
     */
    private $type;

    public function __construct(string $type, int $id)
    {
        $this->type = $type;
        $this->setId($id);
    }

    /**
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

//     /**
//      *
//      * @param string $teamType
//      */
//     public function setType($teamType)
//     {
//         $this->type = $teamType;
//     }
}

