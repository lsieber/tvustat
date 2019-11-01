<?php
namespace tvustat\pts;

class PointUtils
{

    /**
     *
     * @param int $disziplinTypeID
     * @param float $a
     * @param float $b
     * @param float $c
     * @param float $performance
     * @return int|NULL
     */
    public static function calculatePoints(int $disziplinTypeID, float $a, float $b, float $c, float $performance)
    {
        switch ($disziplinTypeID) {
            case 1:
                return self::track($a, $b, $c, $performance);
                break;

            case 2:
                return self::field($a, $b, $c, $performance);
                break;
                
            case 3:
                return self::jumpIAAF($a, $b, $c, $performance);
                break;
                
            default:
                echo "We got a doisziplinType which is not registered with a Formula and thus could not calculate the points";
                return NULL;
                break;
        }
    }

    /**
     *
     * @param float $a
     * @param float $b
     * @param float $c
     * @param float $performance in s
     * @return int
     */
    private static function track(float $a, float $b, float $c, float $performance)
    {
        return intval(floor($a * pow((($b - 100 * $performance) / 100), $c)));
    }

    /**
     *
     * @param float $a
     * @param float $b
     * @param float $c
     * @param float $performance in m
     * @return int
     */
    private static function field(float $a, float $b, float $c, float $performance)
    {
        return intval(floor($a * pow(((100 * $performance - $b) / 100), $c)));
    }
    
    /**
     *
     * @param float $a
     * @param float $b official IAAF parameter b
     * @param float $c
     * @param float $performance in m
     * @return int
     */
    private static function jumpIAAF(float $a, float $b, float $c, float $performance)
    {
        return intval(floor($a * pow(100 * $performance - $b, $c)));
    }
    
}

