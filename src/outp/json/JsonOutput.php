<?php
namespace tvustat;

class JsonOutput implements BestListOutput
{

    /**
     *
     * @var string
     */
    private $json;

    public function __construct(array $array)
    {
        $this->json = json_encode($array);
    }

    /**
     *
     * @return string
     */
    public function getJson()
    {
        return $this->json;
    }
    
    /**
     *
     * {@inheritDoc}
     * @see \tvustat\BestListOutput::toString()
     */
    public function toString()
    {
        return $this->getJson();
    }

}

