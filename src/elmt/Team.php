<?php
namespace tvustat;

class Team extends Athlete
{

    /**
     *
     * @var int
     */
    private $year;

    public function __construct(string $teamName, int $year, Gender $gender, int $id)
    {
        $this->name = $teamName;
        $this->year = $year;
        $this->setGender($gender);
        $this->setId($id);
    }

    /**
     *
     * @return number
     */
    public function getYear()
    {
        return $this->year;
    }

    public function getCategory($year)
    {
        // FIXME
    }

    public function getFirstName()
    {
        return $this->getName();
    }

    public function getLastName()
    {
        return "";
    }
}