<?php

echo "<h1> hello World2 </h1>";

// WEB
$server = "localhost"; 
$user = "tvulive_output";
$pw = "eagzzP5MkLHURaNV";
$db = "tvulive_tvustat";

// DEV;
// $server = "localhost";
// $user = "Input";
// $pw = "TvUInputBestList2019" ;
// $db = "tvustat";


$conn = new \mysqli($server, $user, $pw, $db);

$sql = "SELECT * FROM `athletes` WHERE 1";

$result = $conn->query($sql);
$array = $result->fetch_all(MYSQLI_ASSOC);

foreach ($array as $value) {
    print_r($value);
    echo"</br>";
}


