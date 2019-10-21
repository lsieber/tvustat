<?php
namespace tvustat;

class HtmlOutput implements BestListOutput
{

    /**
     *
     * @var string
     */
    private $html;

    public function __construct(string $html)
    {
        $this->html = $html;
    }

    /**
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \tvustat\BestListOutput::toString()
     */
    public function toString()
    {
        return $this->getHtml();
    }


}

