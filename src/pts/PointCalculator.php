<?php
namespace tvustat\pts;

use tvustat\Connection;

class PointCalculator
{
    
    /**
     * 
     * @var PointParameters
     */
    private $parameters;
    
    
    public function __construct(Connection $conn) {
        $this->parameters = PointParameters::load($conn);
    }

    
    /**
     * 
     * @param int $disziplinId
     * @param int $schemeId
     * @param float $performance
     * @return NULL|int
     */
    public function calculate(int $disziplinId, int  $schemeId, float $performance) {
        $pp = $this->parameters->getParameter($disziplinId, $schemeId);
        return (is_null($pp))? NULL : $pp->perf2pts($performance);
    }
}

