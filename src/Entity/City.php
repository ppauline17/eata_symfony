<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $loisirsReduce = null;

    #[ORM\Column]
    private ?float $wednesdayReduce = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLoisirsReduce(): ?float
    {
        return $this->loisirsReduce;
    }

    public function setLoisirsReduce(float $loisirsReduce): static
    {
        $this->loisirsReduce = $loisirsReduce;

        return $this;
    }

    public function getWednesdayReduce(): ?float
    {
        return $this->wednesdayReduce;
    }

    public function setWednesdayReduce(float $wednesdayReduce): static
    {
        $this->wednesdayReduce = $wednesdayReduce;

        return $this;
    }
}
