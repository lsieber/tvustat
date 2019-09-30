<?php
namespace tvustat;

class DisziplinNameOnly extends Disziplin
{

    public function __construct(string $name, ConnectionPreloaded $conn)
    {
        $sorting = $conn->getSorting(1);
        $orderNumber = 1.0;
        $isTime = true;
        $isDecimal = true;
        $disziplinType = $conn->getDisziplinType(1);
        $teamType = $conn->getTeamType(1);
        $minValue = 0;
        $maxValue = 1000;
        parent::__construct($name, $sorting, $orderNumber, $isTime, $isDecimal, $disziplinType, $teamType, $minValue, $maxValue);
    }
}

