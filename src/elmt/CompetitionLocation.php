<?php
namespace tvustat;

class CompetitionLocation extends DBTableEntry
{

    /**
     *
     * @var string
     */
    private $village;

    /**
     *
     * @var string
     */
    private $facility;

    public function __construct(string $village, ?string $facility, string $id = NULL)
    {
        $this->village = $village;
        $this->facility = is_null($facility) ? "" : $facility;
        if ($id != NULL)
            $this->setId($id);
    }

    /**
     *
     * @return string
     */
    public function getVillage()
    {
        return $this->village;
    }

    /**
     *
     * @return string
     */
    public function getFacility()
    {
        return $this->facility;
    }
}
