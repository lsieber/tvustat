<?php
namespace tvustat;

use config\dbAthletes;
use config\dbTableDescription;
class GetByID
{

    const select = "SELECT * FROM ";

    public function __construct()
    {}

    static function person(ConnectionPreloaded $conn, int $id)
    {
        $r = self::getQuerryResult(dbAthletes::class, $conn, $id);
        return new Person($r[dbAthletes::FIRSTNAME], $r[dbAthletes::LASTNAME], new \DateTime($r[dbAthletes::DATE]), $conn->getGender($r[dbAthletes::GENDERID]));
    }
    
    private static function getQuerryResult(dbTableDescription $desc, ConnectionPreloaded $conn, int $id){
        $colums = $desc::getCollumNames();
        $table = $desc::getTableName();
        
        $sql = self::select . $table . " WHERE " . $colums[0] . "=" . $id;
        
        return $conn.executeSqlToArray($sql);
        
    }
    
}

