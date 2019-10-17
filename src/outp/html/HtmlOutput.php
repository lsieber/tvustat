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

//     /**
//      *
//      * @param string $html
//      */
//     public function setHtml($html)
//     {
//         $this->html = $html;
//     }
}

