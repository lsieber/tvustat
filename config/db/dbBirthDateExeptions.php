<?php
namespace config;


use tvustat\ConnectionPreloaded;
use tvustat\DateFormatUtils;

class dbBirthDateExeptions
{

    public const DBNAME = "athletebirthdateexceptions";

    public const ID = "birthDateExceptionID";

    public const ATHLETEID = "athleteID";

    public const EXEPTION = "additionalDate";
    
    /**
     * Checks if there exists a second alternative Birth Date which was a mistake in the Swiss Athletics best List
     * @param string $athleteName
     * @param \DateTime $birthDate
     * @param ConnectionPreloaded $conn
     * @return boolean
     */
    public static function isAthleteException(string $athleteName, \DateTime $birthDate, ConnectionPreloaded $conn) {
       
        $sql = 'SELECT * FROM ' . dbAthletes::DBNAME.' INNER JOIN '.self::DBNAME.' ON '.dbAthletes::DBNAME .'.'.dbAthletes::ID . '='. self::DBNAME. '.' .self::ATHLETEID;
        $sql .= ' WHERE '. dbAthletes::FULLNAME.' = "'.$athleteName .'"';
        
        $result = $conn->executeSqlToArray($sql);
        
        if(sizeof($result) > 0){
            for ($i = 0; $i < sizeof($result); $i++) {
                $r = $result[$i];
                if ($r[self::EXEPTION] == DateFormatUtils::formatDateForDB($birthDate)) {
                    return true;
                }
            }
        }
        return false;
    }
}

