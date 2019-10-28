<?php
namespace tvustat;

trait HtmlUtils
{

    protected static function row(Performance $performance, ColumnDefinition $columDefinition)
    {
        return self::tr($columDefinition->bestListElements($performance));
    }

    protected static function tr(array $elements)
    {
        $line = "<tr>";
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

