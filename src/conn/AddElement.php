<?php
namespace tvustat;

use config\dbAthletes;
use config\dbTableDescription;

class AddElement
{

    /**
     *
     * @param ConnectionPreloaded $conn
     * @param Athlete $athlete
     * @return string
     */
    public static function person(ConnectionPreloaded $conn, Athlete $athlete)
    {

        // TODO create function
        if (! self::checkAthleteReadyForInsertion($athlete)) {
            echo "Person needs more details for the DB";
            return $athlete->getName();
        }
//         $sql_check_existance = "SELECT ID FROM " . dbAthletes::DBNAME . " WHERE " . dbAthletes::FIRSTNAME . "='" . $athlete->getFirstName() . "' AND " . dbAthletes::LASTNAME . "='" . $athlete->getLastName() . "' AND " . dbAthletes::DATE . "=" . $athlete->getDateForDB();
        // $result_check = $conn->getConn()->query($sql_check_existance);
        // if ($result_check->fetch_all(MYSQLI_ASSOC) == NULL) {
        return self::AddElement($conn, $athlete, new dbAthletes());
        // }

        return "No Insertion Possible";
    }

    private static function checkAthleteReadyForInsertion(Athlete $athlete)
    {
        return ($athlete->getFirstName() != NULL && //
        $athlete->getGender() != NULL && //
        $athlete->getTeamType() != NULL);
    }

    private static function AddElement(ConnectionPreloaded $conn, DBTableEntry $element, dbTableDescription $desc)
    {
        $v = $desc::classToCollumns($element);
        $table = $desc::getTableName();
        $sql = "INSERT INTO " . $table . " VALUES ('Null";

        for ($i = 1; $i < sizeof($v); $i ++) {
            $sql .= "','" . $v[$i];
        }
        $sql .= "')";
        echo $sql;
        $result = $conn->getConn()->query($sql);
        $new_id = $conn->getConn()->insert_id;
        if ($result == 1) {
            echo "Eingabe erfolgreich " . $v[1] . " wurde hinzugefügt";
        } else {
            echo "Eingabe nicht gelungen: ";
        }
        return $v[1] . ", New ID: " . $new_id;
    }
}
