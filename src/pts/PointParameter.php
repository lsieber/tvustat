<?php
namespace tvustat\pts;

class PointParameter
{

    /**
     *
     * @var float
     */
    private $a;

    /**
     *
     * @var float
     */
    private $b;

    /**
     *
     * @var float
     */
    private $c;

    /**
     *
     * @var int
     */
    private $disziplinTypeID;

    function __construct(float $a, float $b, float $c, int $disziplinTypeID)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;

        $this->disziplinTypeID = $disziplinTypeID;
    }

    /**
     *
     * @param float $performance
     * @return NULL|int
     */
    public function perf2pts(float $performance)
    {
        return PointUtils::calculatePoints($this->disziplinTypeID, $this->a, $this->b, $this->c, $performance);
    }
}

