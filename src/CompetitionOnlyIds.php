<?php
namespace tvustat;

final class CompetitionOnlyIds extends Competition
{

    public static function create(int $nameID, int $locationID, \DateTime $date, string $id = NULL)
    {
        echo $nameID . ", " . $locationID . ", " . DateFormatUtils::formatDateForBL($date);
        $location = new CompetitionLocation("NV", "NV", $locationID);
        $name = new CompetitionName("NV", $nameID);
        return new Competition($name, $location, $date, $id = NULL);
    }
}

