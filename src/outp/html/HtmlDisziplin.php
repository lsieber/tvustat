<?php
namespace tvustat;

class HtmlDisziplin
{
    use HtmlUtils;

    public static function create(DisziplinBestListRaw $disziplinBestList, $columnDefinition, int $top = NULL)
    {
//         echo "</br> TOP ParameterC  = " . $top . " </br>";
        
        $disziplinBestListHtml = "<div class='disziplinBestList'><h3>" . $disziplinBestList->getDisziplin()->getName() . "</h3>";
        $disziplinBestListHtml .= "<div class='table-responsiv'><table class='table table-striped'>";
        $disziplinBestListHtml .= self::thead($columnDefinition->bestListHeaders());
        $disziplinBestListHtml .= "<tbody>";
        foreach ($disziplinBestList->getTopList($top) as $performance) {
            $disziplinBestListHtml .= self::row($performance, $columnDefinition);
        }
        $disziplinBestListHtml .= "</tbody></table></div> ";

        return $disziplinBestListHtml . "</div>";
    }
}

