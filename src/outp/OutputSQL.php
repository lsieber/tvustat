<?php
namespace tvustat;

use config\CategoryControl;
use config\dbAthletes;
use config\dbCompetition;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;
use config\dbDisziplin;
use config\dbPerformance;
use config\dbPerformanceDetail;

class OutputSQL
{

    public static function create(string $categoryControl, array $categories, array $disziplins, array $years)
    {
        $sql = self::selectAndJoins();
        $sql .= " WHERE";
        $sql .= self::athletes($categories, $categoryControl);
        $sql .= self::yearsSQL($years);
        $sql .= self::disziplins($disziplins);
        return $sql;
    }

    /**
     * Returns only a sql if the category control is not ALL, Men, or Women.
     * In these cases the Athlete SQL does include the teams
     *
     * @param array $categories
     * @param array $categoryControl
     * @return string
     */
    public static function createTeam(string $categoryControl, array $categories, array $disziplins, array $years)
    {
        switch ($categoryControl) {
            case CategoryControl::ALL:
            case CategoryControl::MEN:
            case CategoryControl::WOMEN:
                break;
            default:
                $sql = self::selectAndJoins();
                $sql .= " WHERE";
                $sql .= self::teams($categories, $categoryControl);
                $sql .= self::yearsSQL($years);
                $sql .= self::disziplins($disziplins);
                return $sql;
        }
        return NULL;
    }

    private static function selectAndJoins()
    {
        $sql = "SELECT * FROM " . dbPerformance::DBNAME;

        $sql .= " INNER JOIN " . dbCompetition::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::COMPETITOINID . " = " . dbCompetition::DBNAME . "." . dbCompetition::ID;
        $sql .= " INNER JOIN " . dbDisziplin::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::DISZIPLINID . " = " . dbDisziplin::DBNAME . "." . dbDisziplin::ID;
        $sql .= " INNER JOIN " . dbAthletes::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::ATHLETEID . " = " . dbAthletes::DBNAME . "." . dbAthletes::ID;
        $sql .= " LEFT JOIN " . dbPerformanceDetail::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::ID . " = " . dbPerformanceDetail::DBNAME . "." . dbPerformanceDetail::PERFORMANCEID;

        $sql .= " INNER JOIN " . dbCompetitionLocations::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::LOCATIONID . " = " . dbCompetitionLocations::DBNAME . "." . dbCompetitionLocations::ID;
        $sql .= " INNER JOIN " . dbCompetitionNames::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::NAMEID . " = " . dbCompetitionNames::DBNAME . "." . dbCompetitionNames::ID;
        return $sql;
    }

    /**
     * Returns a sql if the category control is ALL, Men, or Women.
     * In these cases the Team SQL does not returnn any values
     * * @param array $categories
     *
     * @param array $categoryControl
     * @return string
     */
    private static function athletes($categories, $categoryControl)
    {
        $sql = "";
        switch ($categoryControl) {
            case CategoryControl::ALL:
                break;
            case CategoryControl::MEN:
                $sql .= " (" . dbAthletes::GENDERID . " = " . 1 . " OR";
                $sql .= " " . dbAthletes::GENDERID . " = " . 3 . " ) AND";
                break;
            case CategoryControl::WOMEN:
                $sql .= " (" . dbAthletes::GENDERID . " = " . 2 . " OR";
                $sql .= " " . dbAthletes::GENDERID . " = " . 3 . " ) AND";
                break;
            default:
                $firstCat = TRUE;
                $sql .= " (";
                foreach ($categories as $category) {
                    /**
                     * GENDER
                     */
                    if (! $firstCat) {
                        $sql .= " OR ";
                    }
                    $firstCat = FALSE;

                    $sql .= " ( ";
                    $gender = $category->getGender();
                    if ($gender->getId() == 1 or $gender->getId() == 2) {
                        $sql .= " (" . dbAthletes::GENDERID . " = " . $gender->getId() . " OR";
                        $sql .= " " . dbAthletes::GENDERID . " = " . 3 . " ) AND";
                    } else {
                        if ($gender->getId() != 3) {
                            echo "ERROR something went WRONG HERE in the statment_sex function in the sql class!";
                        }
                    }
                    /**
                     * AGE
                     */

                    $sql .= " EXTRACT(YEAR FROM " . dbCompetition::DATE . ") - EXTRACT(YEAR FROM " . dbAthletes::DATE . ") >= " . $category->getAgeCategory()->getMinAge() . " AND";
                    $sql .= " EXTRACT(YEAR FROM " . dbCompetition::DATE . ") - EXTRACT(YEAR FROM " . dbAthletes::DATE . ") <= " . $category->getAgeCategory()->getMaxAge();
                    $sql .= " ) ";
                }
                $sql .= ") AND ";
        }
        return $sql;
    }

    private static function teams($categories, $categoryControl)
    {
        $sql = "";

        $firstCat = TRUE;
        $sql .= " (";
        foreach ($categories as $category) {
            if (! $firstCat) {
                $sql .= " OR ";
            }
            $firstCat = FALSE;
            $sql .= $category->getId() . "=" . dbAthletes::CATEGORY;
        }
        $sql .= ") AND ";

        return $sql;
    }

    private static function yearsSQL($years)
    {
        $sql = "";
        $AllYears = FALSE; // TODO here you can add a Statement when that all years should be considered
        if (! $AllYears) {
            $list = implode(",", $years);
            $sql .= " EXTRACT(YEAR FROM " . dbCompetition::DATE . ") IN (" . $list . ")";
        }
        return $sql;
    }

    private static function disziplins($disziplins)
    {
        $sql = "";
        $AllDisziplins = sizeof($disziplins) == 0; // TODO here you can add a Statement when that not all disziplin should be considered
        if (! $AllDisziplins) {
            $list = implode(",", $disziplins);
            $sql .= " AND " . dbPerformance::DBNAME . "." . dbPerformance::DISZIPLINID . " IN (" . $list . ")";
        }

        return $sql;
    }
}

