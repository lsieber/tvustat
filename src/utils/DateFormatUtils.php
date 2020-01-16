<?php
namespace tvustat;

class DateFormatUtils
{

    /**
     * 
     * @param \DateTime $date
     * @return boolean
     */
    static function onlyYearValid(\DateTime $date){
        return $date->format("n.j") == "1.1";
    }
    
    /**
     *
     * @param \DateTime $date
     * @return string
     */
    static function formatDateForBL(\DateTime $date = NULL)
    {
        return (is_null($date)) ? "" : self::dateIfPossible($date);
    }

    private static function dateIfPossible(\DateTime $date) {
        return self::onlyYearValid($date) ? $date->format("Y") :$date->format("d.m.Y");
    }
    
    /**
     *
     * @param \DateTime $date
     * @return string
     */
    static function formatBirthYearForBL(\DateTime $date = NULL)
    {
        return (is_null($date)) ? "" : $date->format("Y");
    }

    /**
     *
     * @param \DateTime $date
     * @return string
     */
    static function formatDateForDB(\DateTime $date = NULL)
    {
        return (is_null($date)) ? NULL : $date->format('Y-m-d');
    }

    /**
     *
     * @param string $dateString
     * @return \DateTime
     */
    static function DateTimeFromDB(string $dateString = NULL)
    {
        return (is_null($dateString)) ? NULL : \DateTime::createFromFormat("Y-m-d", $dateString);
    }

    /**
     *
     * @param string $dbTime
     * @return string
     */
    static function convertDateFromDB2BL(string $dbTime = NULL)
    {
        return (is_null($dbTime)) ? NULL : self::formatDateForBL(self::db2DateTime($dbTime));
    }

    static function db2DateTime(string $dbTime = NULL)
    {
        return (is_null($dbTime)) ? NULL : \DateTime::createFromFormat("Y-m-d", $dbTime);
    }

    /**
     *
     * @param \DateTime $date
     * @return NULL|string
     */
    static function formatDateaAsYear(\DateTime $date = NULL)
    {
        return (is_null($date)) ? NULL : $date->format('Y');
    }

    static function nowForDB()
    {
        return (new \DateTime())->format('Y-m-d h:m:s');
    }
}

