<?php
require_once '../vendor/autoload.php';

use tvustat\Competition;
use tvustat\CompetitionLocation;
use tvustat\CompetitionName;
use tvustat\DBMaintainer;
use tvustat\Performance;
use tvustat\TimeUtils;
use tvustat\DateFormatUtils;

$year = 2006;

$path = '../data/' . $year . '/resultsByAthlete/';
// $files = scandir($path);
$files = glob($path . '*.{csv}', GLOB_BRACE);

$db = new DBMaintainer();

$fileCounter = 0;
foreach ($files as $file) {
    $fileCounter ++;

    $athleteNameFromFile = substr($file, strlen($path), - 8);
    echo "Loading " . $athleteNameFromFile . "</br>";

    $insertionCounter = 0;
    if ($fileCounter >= 0) {

        $the_big_array = [];
        // echo $path . $file;

        $lineIndex = 0;

        $birthDate;
        $disziplinName;

        $prevLine;

        $cNId = NULL;
        $cVId = NULL;
        $cDId = NULL;
        $aNId = NULL;
        $pId = NULL;
        $wID = NULL;
        $rID = NULL;
        $dId = NULL;
        $ttId = NULL;

        $h = fopen($file, "r");
        while (($data = fgetcsv($h, 1000, ";")) !== FALSE) {
            // $data = array();
            // foreach ($wrongFormat as $key=> $value) {
            // $data[$key] = $value;
            // }

            // echo "</br>LIIIINE: " . $lineIndex . "</br>";
            // var_dump($data);

            if ($lineIndex == 0) {
                $birthDate = getBirthDate($data[1]);
                // echo DateFormatUtils::formatDateForDB($birthDate);
            } else {
                if ($data[0] == "Nr") {
                    $disziplinName = $prevLine[0];
                    $wId = NULL;
                    $rId = NULL;
                    $dId = NULL;
                    $ttId = NULL;

                    for ($i = 0; $i < sizeof($data); $i ++) {

                        switch ($data[$i]) {
                            case "Name":
                                $aNId = $i;
                                break;
                            case "Ort":
                                $cVId = $i;
                                break;
                            case "Datum":
                                $cDId = $i;
                                break;
                            case "Wettkampf":
                                $cNId = $i;
                                break;
                            case "Rang":
                                $rId = $i;
                                break;
                            case "Wind":
                                $wId = $i;
                                break;
                            case "Resultat":
                                $pId = $i;
                                break;
                            case "Detail":
                                $dId = $i;
                                break;
                            case "Tooltip":
                                $ttId = $i;
                                break;
                            default:
                                ;
                                break;
                        }
                    }
                } else {
                    if (! is_null($cNId) && sizeof($data) > 2) {
                        $athleteName = $data[$aNId];
                        $village = $data[$cVId];
                        $cDate = DateTime::createFromFormat("d.m.Y", $data[$cDId]);
                        $cName = $data[$cNId];
                        $wind = ($wId == NULL) ? NUll : floatval($data[$wId]);
                        $ranking = ($rId == NULL) ? NULL : $data[$rId];
                        $tooltip = ($ttId == NULL) ? NULL : $data[$ttId];
                        $tooltip = ($tooltip == " " || $tooltip == "") ? NULL : $tooltip;

                        $detail = ($dId == NULL) ? NULL : $data[$dId];
                        if ($detail == NULL || $detail == "" || $detail == " ") {
                            $detail = $tooltip;
                        }

                        $perf = floatval(TimeUtils::time2seconds(str_replace("*", "", $data[$pId])));

                        /**
                         * Disziplin
                         */

                        $disziplin = $db->loadbyValues->loadDiziplinByName($disziplinName);
                        if ($disziplin == NULL) {
                            echo "Disziplin " . $disziplinName . " nicht gefunden";
                        }
                        assert($disziplin != NULL);

                        if ($disziplin->getTeamType()->getId() != 1) {
                            $athleteName = $athleteNameFromFile;
                        }
                        /**
                         * Athlete
                         */
                        $athlete = $db->loadbyValues->loadAthleteByName($athleteName);
                        if ($athlete == NULL) {
                            echo "Athlete " . $athleteName . " nicht gefunden";
                        }
                        if (DateFormatUtils::formatDateForDB($athlete->getDate()) != DateFormatUtils::formatDateForDB($birthDate)) {
                            assert($db->checkIfAlternativeBirthDate($athleteName, $birthDate));
                        }
                        assert($athlete != NULL);

                        /**
                         * Competition Name
                         */
                        $competitionName = $db->loadbyValues->loadCompetitionNameByName($cName);
                        if ($competitionName == NULL) {
                            $competitionNameNew = new CompetitionName($cName);
                            $db->addCompetitionName($competitionNameNew);
                            $competitionName = $db->loadbyValues->loadCompetitionNameByName($cName);
                        }
                        if ($competitionName == NULL) {
                            echo "Competition Name " . $cName . " nicht gefunden";
                        }
                        assert($competitionName != NULL);

                        /**
                         * Competition Location
                         */
                        $competitionLocation = $db->loadbyValues->loadCompetitionLocationByName($village);
                        if ($competitionLocation == NULL) {
                            $competitionLocationNew = new CompetitionLocation($village, NULL);
                            $querry = $db->addCompetitionLocation($competitionLocationNew);
                            $competitionLocation = $db->loadbyValues->loadCompetitionLocationByName($village);
                            echo $querry->getMessage();
                        }
                        if ($competitionLocation == NULL) {
                            echo "Competition Location " . $village . " nicht gefunden";
                        }
                        assert($competitionLocation != NULL);

                        /**
                         * Competition
                         */
                        $competition = $db->loadbyValues->loadCompetitionByName($cName, $village, $cDate);
                        if ($competition == NULL) {
                            $competitionNew = new Competition($competitionName, $competitionLocation, $cDate);
                            $db->addCompetition($competitionNew);
                            $competition = $db->loadbyValues->loadCompetitionByName($cName, $village, $cDate);
                        }
                        if ($competition == NULL) {
                            echo "Competition " . $cName . $village . $cDate . " nicht gefunden";
                        }
                        assert($competition != NULL);

                        /**
                         * Performance
                         */
                        $source = $db->getConn()->getSource(1);
                        $preformance = new Performance($disziplin, $athlete, $competition, $perf, $wind, $ranking, $source, NULL, $detail);

                        $querry = $db->addPerformance($preformance);
                        if ($querry->getSuccess()) {
                            $insertionCounter ++;
                        }
                    }
                }
            }
            $prevLine = $data;
            $lineIndex ++;
        }
        fclose($h);
        echo "inserted " . $insertionCounter . " successfully Performances</br>";
    }
}

function getBirthDate(string $dateField)
{
    if ($dateField == "" || $dateField == " ") {
        return NULL;
    }
    return DateTime::createFromFormat("d.m.Y", substr($dateField, - 10));
}