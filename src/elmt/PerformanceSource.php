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

    /**
     * 
     * @var int
     */
    private $sourceTypeID;
    
    public function __construct(string $name, int $id, int $sourceTypeID, string $link = NULL)
    {
        $this->name = $name;
        $this->link = $link;
        $this->sourceTypeID = $sourceTypeID;
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
     * 
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
    
    /**
     * 
     * @return int
     */
    public  function getSourceTypeID() {
        return $this->sourceTypeID;
    }
}

