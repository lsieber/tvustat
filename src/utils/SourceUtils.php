<?php
namespace tvustat;

class SourceUtils
{

    public static function isFromTVUBuch(Performance $performance)
    {
        return ($performance->getSource()->getSourceTypeID() == 3); // TODO A Definciton
    }
}

