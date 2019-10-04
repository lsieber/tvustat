<?php
namespace tvustat;

use config\dbPerformance;
use config\dbCompetition;
use config\dbDisziplin;
use config\dbAthletes;
use config\dbCompetitionLocations;
use config\dbCompetitionNames;

class OutputSQL
{

    public static function create(Gender $gender, array $categories, array $disziplins, array $years)
    {
        $sql = "SELECT * FROM " . dbPerformance::DBNAME;

        $sql .= " INNER JOIN " . dbCompetition::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::COMPETITOINID . " = " . dbCompetition::DBNAME . "." . dbCompetition::ID;
        $sql .= " INNER JOIN " . dbDisziplin::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::DISZIPLINID . " = " . dbDisziplin::DBNAME . "." . dbDisziplin::ID;
        $sql .= " INNER JOIN " . dbAthletes::DBNAME . " ON " . dbPerformance::DBNAME . "." . dbPerformance::ATHLETEID . " = " . dbAthletes::DBNAME . "." . dbAthletes::ID;

        $sql .= " INNER JOIN " . dbCompetitionLocations::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::LOCATIONID . " = " . dbCompetitionLocations::DBNAME . "." . dbCompetitionLocations::ID;
        $sql .= " INNER JOIN " . dbCompetitionNames::DBNAME . " ON " . dbCompetition::DBNAME . "." . dbCompetition::NAMEID . " = " . dbCompetitionNames::DBNAME . "." . dbCompetitionNames::ID;

        $sql .= " WHERE";
        /**
         * GENDER
         */
        if ($gender->getId() == 1 or $gender->getId() == 2) {
            $sql .= " (" . dbAthletes::GENDERID . " = " . $gender->getId() . " OR";
            $sql .= " " . dbAthletes::GENDERID . " = " . 3 . " ) AND";
        } else {
            if ($gender->getId() != 3) {
                echo "ERROR something went WRONG HERE in the statment_sex function in the sql class!";
            }
        }

        /**
         * Category
         */

        $firstCat = TRUE;
        $sql .= " (";
        foreach ($categories as $category) {
            if (! $firstCat) {
                $sql .= " OR ";
            }
            $sql .= "(EXTRACT(YEAR FROM " . dbCompetition::DATE . ") - EXTRACT(YEAR FROM " . dbAthletes::DATE . ") >= " . $category->getAgeCategory()->getMinAge() . " AND";
            $sql .= " EXTRACT(YEAR FROM " . dbCompetition::DATE . ") - EXTRACT(YEAR FROM " . dbAthletes::DATE . ") <= " . $category->getAgeCategory()->getMaxAge() . ")";
            $firstCat = FALSE;
            
        }
        $sql .= ") ";

        /**
         * Year
         */
        $AllYears = FALSE; // TODO here you can add a Statement when that all years should be considered
        if (! $AllYears) {
            $list = implode(",", $years);
            $sql .= " AND EXTRACT(YEAR FROM " . dbCompetition::DATE . ") IN (" . $list . ")";
        }

        /**
         * Disziplins
         */
        $AllDisziplins = TRUE; // TODO here you can add a Statement when that not all disziplin should be considered
        if (! $AllDisziplins) {
            $list = implode(",", $disziplins);
            $sql .= " AND " . dbPerformance::DISZIPLINID . " IN (" . $list . ")";
        }

        return $sql;
    }
}

