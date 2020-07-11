<?php
namespace tvustat;

class ColumnDefinitionCategoryNameLink implements ColumnDefinition
{

    private $categoryUtils;

    public function __construct(CategoryUtils $categoryUtils)
    {
        $this->categoryUtils = $categoryUtils;
    }

    public function bestListHeaders()
    {
        return array( //
            "Resultat",
            "Name",
            "Jg",
            "Ort",
            "Datum",
            "Kategorie"
        );
    }

    public function bestListElements(Performance $performance)
    {
        return array( //
            $performance->getFormatedPerformance(),
            ColDefUtils::athleteLink($performance->getAthlete()),
            DateFormatUtils::formatBirthYearForBL($performance->getAthlete()->getDate()),
            CompetitionUtils::formatCompetitionVillage($performance->getCompetition()
                ->getLocation()),
            DateFormatUtils::formatDateForBL($performance->getCompetition()->getDate()),
            $this->categoryUtils->categoryOf($performance)->getName()
        );
    }

    public function numberBestListElements()
    {
        return sizeof(self::bestListHeaders());
    }
    
}

