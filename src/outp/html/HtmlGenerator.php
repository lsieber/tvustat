<?php
namespace tvustat;

abstract class HtmlGenerator implements BestListOutputGenerator
{
    
//     /**
//      * 
//      * @var ColumnDefinition
//      */
//     protected $columnDefinition;
    
//     /**
//      * 
//      * @param ColumnDefinition $columDefinition
//      */
//     public function __construct(ColumnDefinition $columDefinition){
//         $this->columnDefinition = $columDefinition;
//     }
    

    /**
     * 
     * {@inheritDoc}
     * @see \tvustat\BestListOutputGenerator::createOutput()
     */
    public function createOutput(BestList $bestList, int $top = NULL)
    {
        return $this->createHtml($bestList, $top);
    }

    
    /**
     * 
     * @param BestList $bestlist
     * @return HtmlOutput
     */
    protected abstract function createHtml(BestList $bestlist, int $top = NULL);
}

