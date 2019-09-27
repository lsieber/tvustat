<?php

namespace tvustat;
class YearsInDb
{

    public static function showAsRadioBoxes() {
        echo self::htmlStringYearsAsRadioBoxes();
    }
    
    private static function htmlStringYearsAsRadioBoxes() {
        $dBOutput = new DBOutput();
        $checked = " checked='yes'";
        $htmlString = "";
        foreach ($dBOutput->getYearsInDb() as $year){
            $htmlString .= '<input type="radio" name="year[]" id="'.$year.'" value="'.$year.'"'.$checked.' onclick="no_all_year()"/> '.$year.' <br />';
            $checked = "";
        }
        return$htmlString;       
    }
}

