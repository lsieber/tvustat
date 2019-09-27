<?php
namespace tvustat;

class Performances
{

    /**
     * 
     * @var array
     */
    private $performances = array();
    
    /**
     * 
     * @param Performance $performance
     */
    public function addPerformance(Performance $performance) {
        array_push($this->performances, $performance);
    }
    
    /**
     * 
     * @return array
     */
    public function getPerformances(){
        return $this->performances;
    }
    
}

