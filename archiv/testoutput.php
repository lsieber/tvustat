<?php
use tvustat\BestListHandler;
use tvustat\DBMaintainer;
use tvustat\AgeCategory;
use tvustat\Category;

require_once '../vendor/autoload.php';

$db = new DBMaintainer();

$years = array(
    2019
);
$male = $db->getConn()->getGender(1);

$U16 = new AgeCategory("U16", "U16", 14, 15, 4);
$U18 = new AgeCategory("U18", "U18", 16, 17, 5);
$Aktiv = new AgeCategory("aktiv", "aktiv", 23, 100, 8);

$U16M = new Category($U16, $male, "U16M", "Jugend B", 0);
$U18M = new Category($U18, $male, "M�nner", "Jugend A", 0);
$man = new Category($Aktiv, $male, "M�nner", "M�nner", 0);
$categories = array(
    $man,
    $U16M,
    $U18M
);

$top = 5;

$disziplins = array();

$blh = new BestListHandler($years, $male, $categories, $top, $disziplins, $db);

$blh->callDB();
$blh->formatBestList();
$blh->printTable();

