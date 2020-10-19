<?php
namespace tvustat;

class CategoryUtils
{

    private $sortedCategories = array();

    public function __construct(ConnectionPreloaded $conn)
    {
        $categories = $conn->getAllCategories();
        foreach ($categories as $category) {
            $this->addSecondLevelObjects($category);
        }
    }

    private function addSecondLevelObjects(Category $category)
    {
        for ($ageKey = $category->getAgeCategory()->getMinAge(); $ageKey <= $category->getAgeCategory()->getMaxAge(); $ageKey ++) {
            if (! array_key_exists($category->getGender()->getId(), $this->sortedCategories)) {
                $this->sortedCategories[$category->getGender()->getId()] = array();
            }
            assert(! array_key_exists($ageKey, $this->sortedCategories[$category->getGender()->getId()]));
            $this->sortedCategories[$category->getGender()->getId()][intval($ageKey)] = $category;
        }
    }

    /**
     *
     * @param Performance $performance
     * @return Category
     */
    public function categoryOf(Performance $performance)
    {
        return $this->categoryOfRaw($performance->getAthlete()
            ->getTeamType()
            ->getId(), //
        $performance->getAthlete()
            ->
        getTeamCategory(), //
        $performance->getAthlete()
            ->getGender()
            ->getId(), //
        $performance->getCompetition()
            ->getDate(), //
        $performance->getAthlete()
            ->getDate());
    }

//     public function getAgeOf(Athlete $athlete, Competition $competition)
//     {
//         return getAgeOfRaw($competition->getDate(), $athlete->getDate());
//     }

    public function categoryOfRaw(int $teamTypeId, Category $teamCategory = NULL, int $genderId, \DateTime $competitionDate, \DateTime $athleteBirthDate = NULL)
    {
        if ($teamTypeId == 2) {
            return $teamCategory;
        }
        $age = $this->getAgeOfRaw($competitionDate, $athleteBirthDate);
        return $this->sortedCategories[$genderId][$age];
    }

    public function getAgeOfRaw(\DateTime $competitionDate, \DateTime $athleteBirthDate)
    {
        return intval(DateFormatUtils::formatDateaAsYear($competitionDate) - intval(DateFormatUtils::formatDateaAsYear($athleteBirthDate)));
    }
}

