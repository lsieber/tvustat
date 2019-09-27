<?php
require_once '../vendor/autoload.php';

$connection = new tvustat\ConnectionPreloaded();

test\GenderTest::run();
test\TeamTypeTest::run();
test\DisziplinTypeTest::run();
test\SortingTest::run();


$date =  DateTime::createFromFormat('d/m/Y', '21/04/1993');

$person = new tvustat\Person("Lukas", "Sieber", $date, $connection->getGender(1), $connection);

tvustat\AddElement::person($connection, $person);

echo "Hello World";

?>