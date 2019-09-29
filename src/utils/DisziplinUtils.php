<?php
namespace tvustat;

class DisziplinUtils
{

    public static function checkDisziplinReadyForInsertion(Disziplin $disziplin)
    {
        return ($disziplin->getDisziplinType() != NULL && //
        $disziplin->getMinValue() >= 0 && //
        $disziplin->getMaxValue() > $disziplin->getMinValue() && //
        $disziplin->getName() != NULL && //
        $disziplin->getOrderNumber() != NULL && //
        $disziplin->getSorting() != NULL && //
        $disziplin->getTeamType() != NULL);
    }
}

