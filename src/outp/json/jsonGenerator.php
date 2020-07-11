<?php
namespace tvustat;

class JsonGenerator implements BestListOutputGenerator
{

    private $columnDefinition;

    private $json;

    function __construct(ColumnDefinition $columnDefinition)
    {
        $this->columnDefinition = $columnDefinition;
    }

    /**
     *
     * {@inheritdoc}
     * @see \tvustat\BestListOutputGenerator::createOutput()
     */
    public function createOutput(BestList $bestList, int $top = NULL)
    {
        return $this->createJson($bestList, $top);
    }

    /**
     *
     * @param BestList $bestList
     * @param int $top
     * @return \tvustat\JsonOutput
     */
    private function createJson(BestList $bestList, int $top = NULL)
    {
        $this->json = array();
        foreach ($bestList->getDisziplinBestLists() as $disziplinBestList) {
            self::addDisziplinBestList($disziplinBestList, $this->columnDefinition, $top);
        }
        return new JsonOutput($this->json);
    }

    private function addDisziplinBestList(DisziplinBestListRaw $disziplinBestList, ColumnDefinition $columnDefinition, int $top = NULL)
    {
        $disJson = array();
        foreach ($disziplinBestList->getTopList($top) as $performance) {
            array_push($disJson, $columnDefinition->bestListElements($performance));
        }
        $value = array(
            "disziplinName" => $disziplinBestList->getDisziplin()->getName(),
            "columnHeaders" => $columnDefinition->bestListHeaders(),
            "performances" => $disJson
        );

        array_push($this->json, $value);
    }
}

