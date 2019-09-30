<?php
namespace tvustat;

final class CompetitionOnlyIds extends Competition
{

    public function __construct(int $nameID, int $locationID, \DateTime $date, string $id = NULL)
    {
        $location = new CompetitionLocation("", "", $locationID);
        $name = new CompetitionName("", $nameID);
        parent::__construct($name, $location, $date, $id = NULL);
    }
}

