

<?php
require_once '../vendor/autoload.php';
?>

<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="inc/style.css">
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script type=module src="../js/output/output.js"></script>
</head>

<body onload="onload()">
	
	<a href="index.html">Zurück zur Bestenliste</a>
	
    <?php
    use tvustat\AthleteBestList;
    use tvustat\DBMaintainer;
    $db = new DBMaintainer();
    $athleteId = $_POST["athleteID"];
    $bl = new AthleteBestList($athleteId, $db);
    $bl->callDB();
    $bl->formatBestList("ALL"); // TODO
    $bl->printTable();
    ?>

</body>