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
        if ($performance->getAthlete()->getTeamType()->getId() == 2) {
            return $performance->getAthlete()->getTeamCategory();
        }
        $genderId = $performance->getAthlete()
            ->getGender()
            ->getId();
        $age = $this->getAgeOf($performance->getAthlete(), $performance->getCompetition());
        return $this->sortedCategories[$genderId][$age];
    }

    public function getAgeOf(Athlete $athlete, Competition $competition)
    {
        return intval(DateFormatUtils::formatDateaAsYear($competition->getDate())) - intval(DateFormatUtils::formatDateaAsYear($athlete->getDate()));
    }
    
}

