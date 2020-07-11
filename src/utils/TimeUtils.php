<?php
namespace tvustat;

class TimeUtils
{

    public static function time2seconds($time)
    {
        $exploded_time = explode(":", $time);
        if (sizeof($exploded_time) == 1) {
            return $exploded_time[0];
        } elseif (sizeof($exploded_time) == 2) {
            $seconds = $exploded_time[0] * 60 + $exploded_time[1];
            return $seconds;
        } elseif (sizeof($exploded_time) == 3) {
            $seconds = $exploded_time[0] * 3600 + $exploded_time[0] * 60 + $exploded_time[1];
            return $seconds;
        } else {
            echo "Error in time to seconds conversion (function time2seconds)";
            return false;
        }
        print_r($exploded_time);
    }

    public static function twoDigitsEnd($perf)
    {
        $splited = explode(".", $perf);
        (sizeof($splited) != 1) ?: $splited[1] = "00";
        if (strlen($splited[1]) < 2) {
            $splited[1] = $splited[1] . "0";
            return self::twoDigitsEnd(implode(".", $splited));
        } else {
            return implode(".", $splited);
        }
    }

    public static function second2time($seconds)
    {
        $hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        $secs = floor($seconds % 60);
        $hund = explode(".", $seconds);
        (sizeof($hund) != 1) ?: $hund[1] = "00";
        if ($seconds < 10) {
            return sprintf('%01d.%02d', $secs, $hund[1]);
        } elseif ($seconds < 60) {
            return sprintf('%02d.%02d', $secs, $hund[1]);
        } elseif ($seconds < 600) {
            return sprintf('%01d:%02d.%02d', $mins, $secs, $hund[1]);
        } elseif ($seconds < 3600) {
            return sprintf('%02d:%02d.%02d', $mins, $secs, $hund[1]);
        } elseif ($seconds < 36000) {
            return sprintf('%01d:%02d:%02d.%02d', $hours, $mins, $secs, $hund[1]);
        } else {
            return "CONVERSION ERROR: more than 10 hours...";
        }
    }
}

?>