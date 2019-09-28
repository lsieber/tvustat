<?php
use tvustat\DBMaintainer;
use tvustat\DisziplinNameOnly;

require_once '../vendor/autoload.php';


$get_mitglied = ($_POST['type'] == 'mitglied') ? TRUE : FALSE;
$get_year = ($_POST['type'] == 'year') ? TRUE : FALSE;
$disziplin_exists = ($_POST['type'] == 'disziplinExists') ? TRUE : FALSE;
$get_lauf_disziplin = ($_POST['type'] == 'Lauf_of_Disziplin') ? TRUE : FALSE;

$db = new DBMaintainer();

if ($disziplin_exists) {
    $disziplinExists = $db->checkDisziplinExists(new DisziplinNameOnly($_POST["disziplin"], $db->getConn()));
    $converted_res = ($disziplinExists) ? 'true' : 'false';
    $result = array("disziplinExists"=> $converted_res, "disziplinName" => $_POST["disziplin"]);
    echo json_encode($result);
}

// $max_num_rows = 30;
if ($get_mitglied) {
//     $vorname = implode([
//         $_POST['vorname'],
//         "%"
//     ]);
//     $name = implode([
//         $_POST['name'],
//         "%"
//     ]);
//     $jg = $_POST['jg'];
//     $sql = "SELECT * FROM `mitglied` WHERE Name LIKE '$name' AND Vorname LIKE '$vorname' ORDER BY Geschlecht, Vorname, Name LIMIT $max_num_rows";

//     $result = $conn->query($sql);
//     $array_result = $result->fetch_all(MYSQLI_ASSOC);

//     if (sizeof($array_result) > 0) {
//         echo "<b>Existierende Athleten/innen mit den Eingaben:</b></br>";
//         foreach ($array_result as $key => $person) {
//             $sex = ($person['Geschlecht'] == 1) ? "W" : (($person['Geschlecht'] == 2) ? "M" : "Team");
//             echo $person['Vorname'] . " " . $person['Name'] . ", " . $person['Jg'] . ", " . $sex . ", ";
//             $aktiv_bis = $person["Jg"] + $person["aktiv"] + 17;
//             echo "<input type='number' style='width: 4em' value='" . $aktiv_bis . "'/> </br>";
//         }
//         if (sizeof($array_result) == $max_num_rows) {
//             echo "<b>Für andere Einträge bitte mehr Angaben machen!!!</b>";
//         }
//     } else {
//         echo "Keine Athletinnen entsprechen den Eingaben";
//     } // code...
}
if ($get_year) {
//     $sql = "SELECT DISTINCT Jahr FROM `wettkampf` WHERE ID < 1950 OR ID>2080 ORDER BY Jahr DESC LIMIT $max_num_rows";
//     $result = $conn->query($sql);
//     $array_result = $result->fetch_all(MYSQLI_ASSOC);
//     foreach ($array_result as $key => $value) {
//         $checked = ($value['Jahr'] == date("Y")) ? "checked" : "";
//         echo '<input type="radio" name="year[]" id="' . $value["Jahr"] . '" value=' . $value["Jahr"] . ' ' . $checked . ' onclick="no_all_year()"/> ' . $value["Jahr"] . '<br />';
//     }
}

if ($get_lauf_disziplin) {
//     $sql = "SELECT * FROM `disziplin` WHERE ID=" . $_POST["disziplin_id"] . "";
//     $result = $conn->query($sql);
//     $array_result = $result->fetch_all(MYSQLI_ASSOC);
//     print_r($array_result[0]["Lauf"]);
}
?>
