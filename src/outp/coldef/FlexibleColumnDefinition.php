<?php
namespace tvustat;

class FlexibleColumnDefinition implements ColumnDefinition
{

    private $columns;

    public function __construct(DisziplinBestList $disziplinBestList)
    {}

    public function bestListHeaders()
    {}

    public function bestListElements(Performance $performance)
    {}

    public function numberBestListElements()
    {}
}

