<?php
namespace tvustat;

class HtmlGeneratorDisziplinIndiv extends HtmlGenerator
{

    use HtmlUtils;

    /**
     *
     * @var string
     */
    private $html;

    /**
     *
     * @var BestListTitle
     */
    private $title;

    private $withDetail;

    private $woDetail;

    public function __construct(ColumnDefinition $withDetail, ColumnDefinition $woDetail, BestListTitle $title)
    {
        $this->title = $title;
        $this->withDetail = $withDetail;
        $this->woDetail = $woDetail;
    }

    /**
     *
     * {@inheritdoc}
     * @see \tvustat\HtmlGenerator::createHtml()
     */
    public function createHtml(BestList $bestList, int $top = NULL)
    {
//         echo "</br> TOP Parameter  = " . $top . " </br>";
        
        $this->html = "<div class='csc-header csc-header-n1'><h1 class='csc-firstHeader'>";
        $this->html .= $this->title->getTitle() . "</h1></div>";
        foreach ($bestList->getDisziplinBestLists() as $disBestList) {
            if (self::hasdetail($disBestList, $top)) {
                $this->html .= HtmlDisziplin::create($disBestList, $this->withDetail, $top);
            } else {
                $this->html .= HtmlDisziplin::create($disBestList, $this->woDetail, $top);
            }
        }
        return new HtmlOutput($this->html);
    }

    private static function hasdetail(DisziplinBestListRaw $disBestList, int $top = NULL)
    {
        foreach ($disBestList->getTopList($top) as $performance) {
            if ($performance->getDetail() != NULL) {
                return true;
            }
        }
        return false;
    }
}

