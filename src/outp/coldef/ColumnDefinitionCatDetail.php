<?php
namespace tvustat;

class ColumnDefinitionCatDetail implements ColumnDefinition
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
            "Kategorie",
            "Detail"
        );
    }

    public function bestListElements(Performance $performance)
    {
        return array( //
            $performance->getFormatedPerformance(),
            $performance->getAthlete()->getFullName(),
            DateFormatUtils::formatBirthYearForBL($performance->getAthlete()->getDate()),
            $performance->getCompetition()
                ->getLocation()
                ->getVillage(),
            DateFormatUtils::formatDateForBL($performance->getCompetition()->getDate()),
            $this->categoryUtils->categoryOf($performance)->getName(),
            $performance->getDetail()
        );
    }

    public function numberBestListElements()
    {
        return sizeof(self::bestListHeaders());
    }
}

