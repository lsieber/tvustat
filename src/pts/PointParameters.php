<?php
namespace tvustat\pts;

use config\dbDisziplinTypes;
use config\dbPointParameters;
use config\dbPointSchemes;
use tvustat\Connection;

class PointParameters
{

    /**
     *
     * @var array
     */
    private $parameters;

    private function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public static  function load(Connection $conn)
    {
        $r = $conn->executeSqlToArray(self::getSQL());

        $parameters = array();
        foreach ($r as $v) {
            $pp = new PointParameter($v[dbPointParameters::A], $v[dbPointParameters::B], $v[dbPointParameters::C], $v[dbPointParameters::FORMULATYPEID]);
            $dId = $v[dbPointParameters::DISZIPLINID];
            $sId = $v[dbPointParameters::SCHEMEID];

            if (!array_key_exists($dId, $parameters)) {
                $parameters[$dId] = array();
            }
            assert(! array_key_exists($sId, $parameters[$dId]));
            $parameters[$dId][$sId] = $pp;
        }
        return new self($parameters);
    }

    /**
     * 
     * @param int $disziplinId
     * @param int $schemeId
     * @return PointParameter|NULL
     */
    public function getParameter(int $disziplinId,int $schemeId )
    {
        if (array_key_exists($disziplinId, $this->parameters)) {
            if (array_key_exists($schemeId, $this->parameters[$disziplinId])) {
                return $this->parameters[$disziplinId][$schemeId];
            }
        }
        return NULL;
    }

    private static function getSQL()
    {
        $sql = "SELECT * FROM " . dbPointParameters::DBNAME;
        $sql .= " INNER JOIN " . dbPointSchemes::DBNAME . " ON " . dbPointParameters::DBNAME . "." . dbPointParameters::SCHEMEID . "=" . dbPointSchemes::DBNAME . "." . dbPointSchemes::ID;
        $sql .= " INNER JOIN " . dbDisziplinTypes::DBNAME . " ON " . dbPointParameters::DBNAME . "." . dbPointParameters::FORMULATYPEID . "=" . dbDisziplinTypes::DBNAME . "." . dbDisziplinTypes::ID;
        return $sql;
    }
}

