<?php
namespace tvustat;

interface ColumnDefinition
{
    /**
     * @return array
     */
    public function bestListHeaders();
    
    /**
     * 
     * @param Performance $performance
     * @param CategoryUtils $categoryutils
     * @return array
     */
    public function bestListElements(Performance $performance);
   
    /**
     * @return int
     */
    public function numberBestListElements();
    
}

