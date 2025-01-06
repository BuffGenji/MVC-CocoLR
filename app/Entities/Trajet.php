<?php

declare(strict_types=1);
namespace App\Entities;

class Trajet
{
    private int $id;
    private int $person_id;
    private string $destination;
    private string $departure;
    private string $start_date;
    private string $end_date;


    public function __construct(array $data = [])
    {
        $this->hydrate($data);
    }


    public function hydrate(array $data): void
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    // Getter and Setter for id
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Getter and Setter for person_id
    public function getPersonId(): int
    {
        return $this->person_id;
    }

    public function setPerson_Id(int $person_id): void
    {
        $this->person_id = $person_id;
    }

    // Getter and Setter for destination
    public function getDestination(): string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): void
    {
        $this->destination = $destination;
    }


    // Getter and Setter for departure
    public function getDeparture(): string
    {
        return $this->departure;
    }

    public function setDeparture(string $departure): void
    {
        $this->departure = $departure;
    }

    // Getter and Setter for start_date
    public function getStartDate(): string
    {
        return $this->start_date;
    }

    public function setStart_Date(string $start_date): void
    {
        $this->start_date = (new \DateTime($start_date))->format('Y-m-d');
    }

    // Getter and Setter for end_date
    public function getEndDate(): string
    {
        return $this->end_date;
    }

    public function setEnd_Date(string $end_date): void
    {
        $this->end_date = (new \DateTime($end_date))->format('Y-m-d');
    }   
}
