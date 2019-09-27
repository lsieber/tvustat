<?php
namespace tvustat;

class Person extends Athlete
{


    public function __construct(string $firstName, string $lastName, \DateTime $birthDate, Gender $gender, ConnectionPreloaded $conn, int $id = null)
    {
        $teamType = $conn->getTeamType(TeamTypeUtils::ofClass(self::class));
        parent::__construct($firstName, $lastName, $birthDate, $gender, $teamType, $id);
    }

    public function getInfo()
    {
        return $this->firstName . " " . $this->lastName . ", born: " . $this->getDateAsString();
    }

    public function equals(Person $person)
    {
        return $this->firstName == $person->firstName && //
        $this->lastName == $person->lastName && //
        $this->birthDate == $person->birthDate;
    }

}
?>