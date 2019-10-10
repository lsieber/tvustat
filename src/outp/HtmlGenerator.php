<?php
namespace tvustat;

class HtmlGenerator
{
    use BestListColumnDefinition;
    use RecordsColumnDefinition;

    public static function htmlTableBestListHeader()
    {
        return self::thead(self::bestListHeaders());
    }

    public static function htmlOfDisziplinBestListForTableBestList(DisziplinBestList $disziplinBestList, CategoryUtils $categoryUtils)
    {
        $disziplinBestListHtml = "<tr><td colspan=" . self::numberBestListElements() . "><b>" . $disziplinBestList->getDisziplin()->getName() . "</b></td></tr>";
        foreach ($disziplinBestList->getTopList() as $performance) {
            $disziplinBestListHtml .= self::htmlOfPerformanceForTableBestList($performance, $categoryUtils);
        }
        return $disziplinBestListHtml;
    }

    private static function htmlOfPerformanceForTableBestList(Performance $performance, CategoryUtils $categoryUtils)
    {
        return self::tr(self::bestListElements($performance, $categoryUtils));
    }

    // OLD CODE
    // public static function htmlOfDisziplinBestListForTableBestList(DisziplinBestList $disziplinBestList)
    // {
    // $disziplinBestListHtml = "<tr><td colspan=" . self::numberBestListElements() . "><b>" . $disziplinBestList->getDisziplin()->getName() . "</b></td></tr>";
    // foreach ($disziplinBestList->getTopList() as $performance) {
    // $disziplinBestListHtml .= self::htmlOfPerformanceForTableBestList($performance);
    // }
    // return $disziplinBestListHtml;
    // }

    // private static function htmlOfPerformanceForTableBestList(Performance $performance)
    // {
    // return self::tr(self::bestListElements($performance));
    // }
    public static function htmlTableRecordtHeader()
    {
        return self::thead(self::recordHeaders());
    }

    private static function htmlOfPerformanceForRecords(Performance $performance)
    {
        return self::tr(self::recordElements($performance));
    }

    public static function htmlOfDisziplinBestListForRecordTable(DisziplinBestList $disziplinBestList)
    {
        $firstPerformance = array_values($disziplinBestList->getTopList())[0];
        return self::htmlOfPerformanceForRecords($firstPerformance);
    }

    private static function tr(array $elements)
    {
        $line = "<tr>";
        foreach ($elements as $element) {
            $line .= self::td($element);
        }
        return $line . "</tr>";
    }

    private static function td($string)
    {
        return "<td>" . $string . "</td>";
    }

    private static function thead(array $strings)
    {
        $head = "<thead><tr>";
        foreach ($strings as $string) {
            $head .= "<th>" . $string . "</th>";
        }
        return $head . "</tr></thead>";
    }
}

