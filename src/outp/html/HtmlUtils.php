<?php
namespace tvustat;

trait HtmlUtils
{

    protected static function row(Performance $performance, ColumnDefinition $columDefinition)
    {
        return self::tr($columDefinition->bestListElements($performance), $performance->getId());
    }

    protected static function tr(array $elements, $trId = null)
    {
        $id = (is_null($trId)) ? "" : " id='" . $trId . "'";
        $line = "<tr" . $id . ">";
        foreach ($elements as $element) {
            $line .= self::td($element);
        }
        return $line . "</tr>";
    }

    protected static function td($string)
    {
        return "<td>" . $string . "</td>";
    }

    protected static function thead(array $strings)
    {
        $head = "<thead><tr>";
        foreach ($strings as $string) {
            $head .= "<th>" . $string . "</th>";
        }
        return $head . "</tr></thead>";
    }
}

