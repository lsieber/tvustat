<?php
namespace tvustat;

class ColumnDefinitionCategoryNoName implements ColumnDefinition
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
            "Datum",
            "Kategorie",
            "Wettkampf",
            "Ort"

        );
    }

    public function bestListElements(Performance $performance)
    {
        return array( //
            $performance->getFormatedPerformance(),
            DateFormatUtils::formatDateForBL($performance->getCompetition()->getDate()),
            $this->categoryUtils->categoryOf($performance)->getName(),
            CompetitionUtils::formatCompetitionName($performance->getCompetition()
                ->getName()),
            CompetitionUtils::formatCompetitionVillage($performance->getCompetition()
                ->getLocation())
        );
    }

    public function numberBestListElements()
    {
        return sizeof(self::bestListHeaders());
    }
}

