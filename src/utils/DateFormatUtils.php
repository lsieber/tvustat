<?php
namespace tvustat;

use config\DefaultSettings;

class DateFormatUtils
{

    /**
     * 
     * @param \DateTime $date
     * @return string
     */
    static function formatDateForBL(\DateTime $date)
    {
        return $date->format(DefaultSettings::DATEFORMAT);
    }

    /**
     * 
     * @param \DateTime $date
     * @return string
     */
    static function formatDateaAsYear(\DateTime $date)
    {
        return $date->format('Y');
    }
}

