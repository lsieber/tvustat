<?php
namespace tvustat;

class BestListTitleFromString implements BestListTitle
{

    /**
     *
     * @var string
     */
    private $titleString;

    /**
     *
     * @param string $titleString
     */
    function __construct(string $titleString)
    {
        $this->titleString = $titleString;
    }

    /**
     *
     * {@inheritdoc}
     * @see \tvustat\BestListTitle::getTxtFileTitle()
     */
    public function getTxtFileTitle()
    {
        return $this->getTitle();
    }

    /**
     *
     * {@inheritdoc}
     * @see \tvustat\BestListTitle::getTitle()
     */
    public function getTitle()
    {
        return $this->titleString;
    }
}

