<?php
namespace tvustat;

class PerformanceSource extends DBTableEntry
{

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var string
     */
    private $link;

    public function __construct(string $name, int $id, string $link = NULL)
    {
        $this->name = $name;
        $this->link = $link;
        $this->setId($id);
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     */
    public function getLink()
    {
        return $this->link;
    }
}

