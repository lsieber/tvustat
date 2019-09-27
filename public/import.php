<?php
use tvustat\SwissAthleticsExportLoader;

require_once '../vendor/autoload.php';

echo "Hello World";

$loader = new SwissAthleticsExportLoader();

$loader->readFile(SwissAthleticsExportLoader::FILEPATH);

?>