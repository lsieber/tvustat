<?php
namespace tvustat;

class StringOutput implements BestListOutput
{

    /**
     *
     * @var string
     */
    private $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    /**
     *
     * {@inheritdoc}
     * @see \tvustat\BestListOutput::toString()
     */
    public function toString()
    {
        return $this->string;
    }
}

