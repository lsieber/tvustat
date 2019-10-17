<?php
namespace tvustat;

interface BestListOutputGenerator
{
    /**
     * 
     * @param BestList $bestList
     * @return BestListOutput
     */
    public function createOutput(BestList $bestList);
}

