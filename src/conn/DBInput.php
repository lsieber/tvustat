<?php

class DBInput extends Connection
{

    function setPersonInDB(Person $person)
    {
        // TODO create function
        if (! $person->checkFieldsFilled()) {
            echo "Person needs more details for the DB";
            return $person->getInfo();
        }
        $sql_check_existance = "SELECT ID FROM mitglied WHERE Vorname='" . $person->getFirstName() . "' AND Name='" . $person->getLastName() . "' AND Jg=" . $person->getBorn();
        $result_check = $this->conn->query($sql_check_existance);
        if ($result_check->fetch_all(MYSQLI_ASSOC) == NULL) {
            $sql = "INSERT INTO mitglied VALUES ('Null','" . $person->getLastName() . "','" . $person->getFirstName() . "','" . $person->getBorn() . "','" . $person->getGender()->getNumericalValue() . "','" . $person->getVisibileUntil() . "', NULL)";
            $result = $this->conn->query($sql);
            $mitglied_id = $this->conn->insert_id;
            if ($result == 1) {
                echo "Eingabe erfolgreich " . $person->getFullName() . " wurde hinzugef�gt";
            } else {
                echo "Eingabe nicht gelungen: ";
            }
            return $person->getInfo() . ", New ID: " . $mitglied_id;
        }
        echo "<b>Eingabe nicht geglückt. Folgende Eingabe besteht bereits: </b>";
    }

    // TODO Check
    function setDisziplinInDB(disziplin $disziplin)
    {
        $name = $disziplin->getName();
        $lauf = $disziplin->getLauf();
        $minValue = $disziplin->getMinValue();
        $maxValue = $disziplin->getMaxValue();
        $length = strlen($name);
        $sql_val_kat = $disziplin->getSql_val_kat();

        $sql = "INSERT INTO disziplin VALUES ('Null','" . $name . "','" . $lauf . "','" . $length . "'," . $sql_val_kat . ",'" . $maxValue . "','" . $minValue . "','','Null','Null')";
        $result = $this->conn->query($sql);
        if ($result == 1) {
            echo "Eingabe erfolgreich";
        } else {
            echo "Eingabe nicht gelungen";
        }
        return "Last Disziplin ID: " . $this->conn->insert_id . " name: " . $name;
    }

    // TODO Check
    function setWettkampfInDB(Competition $competition)
    {
        $date = $competition->getDate()->format('Y-m-d');
        $year = $competition->getYear();
        ($year > 1950 and $year < 2049) ? $sql = "INSERT INTO wettkampf VALUES ('Null','" . $competition->getPlace() . "','" . $date . "','" . $competition->getName() . "',1," . $year . ")" : print_r("unexpected year.");
        $result = $this->conn->query($sql);
        if ($result == 1) {
            echo "Eingabe erfolgreich";
        } else {
            echo "Eingabe nicht gelungen";
        }
    }

    /**
     *
     * @param int $competitionId
     * @param int $disziplinId
     * @param int $personId
     * @return mysqli_result|boolean
     */
    function setPerformance(int $competitionId, int $disziplinId, int $personId, $performance)
    {
        $perfomanceAsDouble = TimeUtils::time2seconds($performance);
        $sql = "INSERT INTO bestenliste VALUES ('Null','" . $personId . "','" . $competitionId . "','" . $disziplinId . "','" . $perfomanceAsDouble . "')";
        return $this->conn->query($sql);
    }

    // function deleteDbEntry(int $id)
    // {
    // $sql = "DELETE FROM `bestenliste` WHERE `bestenliste`.`ID` = " . $id . ";";
    // $result = $this->conn->query($sql);
    // ($result == 1) ? print_r('ID: ' . $id . ' erfolgreich gelöscht!</br></br>') : print_r("Löschen fehlgeschlagen!!!</br></br>");
    // }
}
?>
