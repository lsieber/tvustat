<?php
namespace tvustat;

class HtmlGeneratorBasic extends HtmlGenerator
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

    private $columnDefinition;

    public function __construct(ColumnDefinition $columDefinition, BestListTitle $title)
    {
        // parent::__construct($columDefinition);
        $this->columnDefinition = $columDefinition;
        $this->title = $title;
    }

    /**
     *
     * {@inheritdoc}
     * @see \tvustat\HtmlGenerator::createHtml()
     */
    protected function createHtml(BestList $bestList, int $top = NULL)
    {
        $this->html = "<div class='csc-header csc-header-n1'><h1 class='csc-firstHeader'>";
        $this->html .= $this->title->getTitle() . "</h1></div>";
        $this->html = "<div class='table-responsiv'><table class='table table-striped'>";
        $this->html .= self::thead($this->columnDefinition->bestListHeaders());
        $this->html .= "<tbody>";
        foreach ($bestList->getDisziplinBestLists() as $disBestList) {
            $this->html .= $this->htmlOfDisziplin($disBestList, $top);
        }
        $this->html .= "</tbody></table></div> ";
        return new HtmlOutput($this->html);
    }

    private function htmlOfDisziplin(DisziplinBestList $disziplinBestList, int $top = NULL)
    {
        $disziplinBestListHtml = "<tr><td colspan=" . $this->columnDefinition->numberBestListElements() . "><b>" . $disziplinBestList->getDisziplin()->getName() . "</b></td></tr>";
        foreach ($disziplinBestList->getTopList($top) as $performance) {
            $disziplinBestListHtml .= self::row($performance, $this->columnDefinition);
        }
        return $disziplinBestListHtml;
    }
}

