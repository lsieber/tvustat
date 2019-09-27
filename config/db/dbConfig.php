<?php
namespace config;

class dbConfig
{

    private $config = array();

    public function __construct()
    {
        $this->loadDBTableDescriptions(array(
            new dbAthletes(),
            new dbCompetition(),
            new dbCompetitionNames(),
            new dbCompetitionLocations(),
            new dbDisziplin()
        ));
    }


    private function loadDBTableDescriptions(array $tableDecriptions)
    {
        foreach ($tableDecriptions as $desc) {
            $this->addDBTableDescription($desc);
        }
    }

    private function addDBTableDescription(dbTableDescription $desc)
    {
        $this->config[get_class($desc)] = $desc;
    }

    /**
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * 
     * @param string $className
     * @return NULL|dbTableDescription
     */
    public function getTableDesc(string $className)
    {
        return (isset($this->config[$className])) ? $this->config[$className] : NULL;
    }
}

