<?php
namespace tvustat;

class DisziplinType extends DBTableEntry
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
}

