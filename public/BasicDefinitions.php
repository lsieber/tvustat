<?php
use tvustat\DBMaintainer;
use tvustat\ConnectionPreloaded;
use tvustat\Gender;
use tvustat\Sorting;
use tvustat\DisziplinType;
use tvustat\TeamType;

require_once '../vendor/autoload.php';

$conn = new ConnectionPreloaded();

$result = array( //
    "genders" => arrayOfGenders($conn), //
    "sortings" => arrayOfSortings($conn), //
    "disziplinTypes" => arrayOfDisziplinTypes($conn), //
    "teamTypes" => arrayOfTeamTypes($conn), //
    "pointSchemeNames" => $conn->getPointSchemeNames(), //
    "pointSchemes" => $conn->getPointSchemes(), //
    "pointParameter" => $conn->getPointParameters()
);

echo json_encode($result);

/**
 *
 * @param ConnectionPreloaded $conn
 */
function arrayOfGenders(ConnectionPreloaded $conn)
{
    $r = array();
    foreach ($conn->getAllGenders() as $k => $gender) {
        $r[$k] = arrayOfGender($gender);
    }
    return $r;
}

function arrayOfGender(Gender $gender)
{
    return array(
        "name" => $gender->getName(),
        "shortName" => $gender->getShortName(),
        "id" => $gender->getId()
    );
}

/**
 *
 * @param ConnectionPreloaded $conn
 */
function arrayOfSortings(ConnectionPreloaded $conn)
{
    $r = array();
    foreach ($conn->getAllSortings() as $k => $v) {
        $r[$k] = arrayOfSorting($v);
    }
    return $r;
}

function arrayOfSorting(Sorting $sorting)
{
    return array(
        "direction" => $sorting->getSortingDirection(),
        "sql" => $sorting->getSortingDirectionSQL(),
        "id" => $sorting->getId()
    );
}

/**
 *
 * @param ConnectionPreloaded $conn
 */
function arrayOfDisziplinTypes(ConnectionPreloaded $conn)
{
    $r = array();
    foreach ($conn->getAllDisziplinTypes() as $k => $v) {
        $r[$k] = arrayOfDisziplinType($v);
    }
    return $r;
}

function arrayOfDisziplinType(DisziplinType $dT)
{
    return array(
        "type" => $dT->getType(),
        "id" => $dT->getId()
    );
}

/**
 *
 * @param ConnectionPreloaded $conn
 */
function arrayOfTeamTypes(ConnectionPreloaded $conn)
{
    $r = array();
    foreach ($conn->getAllTeamTypes() as $k => $v) {
        $r[$k] = arrayOfTeamType($v);
    }
    return $r;
}

function arrayOfTeamType(TeamType $tT)
{
    return array(
        "type" => $tT->getType(),
        "id" => $tT->getId()
    );
}

?>