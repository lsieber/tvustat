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
    static function formatDateForDB(\DateTime $date)
    {
        return $date->format('Y-m-d');
    }
    
    /**
     * 
     * @param string $dateString
     * @return \DateTime
     */
    static  function DateTimeFromDB(string $dateString) {
        return \DateTime::createFromFormat("Y-m-d", $dateString);
    }

    /**
     */
    static function formatDateaAsYear(\DateTime $date)
    {
        return $date->format('Y');
    }
    
    static function nowForDB() {
        return (new \DateTime())->format('Y-m-d h:m:s');
    }
}

