<?php
namespace tvustat;

class CompetitionName extends DBTableEntry
{

    /**
     *
     * @var string
     */
    private $competitionName;

    /**
     *
     * @var string
     */
    private $facility;

    public function __construct(string $competitionName, string $id = NULL)
    {
        $this->competitionName = $competitionName;
        if ($id != NULL)
            $this->setId($id);
    }

    /**
     *
     * @return string
     */
    public function getCompetitionName()
    {
        return $this->competitionName;
    }
    
}

