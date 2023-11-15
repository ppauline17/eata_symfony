<?php

namespace App\Entity;

use App\Repository\PriceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PriceRepository::class)]
class Price
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $familyQuotient = null;

    #[ORM\Column(nullable: true)]
    private ?float $halfDay = null;

    #[ORM\Column(nullable: true)]
    private ?float $fullDay = null;

    #[ORM\Column(nullable: true)]
    private ?float $morning = null;

    #[ORM\Column(nullable: true)]
    private ?float $eveningFirstHour = null;

    #[ORM\Column(nullable: true)]
    private ?float $eveningHalfHour = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFamilyQuotient(): ?string
    {
        return $this->familyQuotient;
    }

    public function setFamilyQuotient(string $familyQuotient): static
    {
        $this->familyQuotient = $familyQuotient;

        return $this;
    }

    public function getHalfDay(): ?float
    {
        return $this->halfDay;
    }

    public function setHalfDay(?float $halfDay): static
    {
        $this->halfDay = $halfDay;

        return $this;
    }

    public function getFullDay(): ?float
    {
        return $this->fullDay;
    }

    public function setFullDay(?float $fullDay): static
    {
        $this->fullDay = $fullDay;

        return $this;
    }

    public function getMorning(): ?float
    {
        return $this->morning;
    }

    public function setMorning(?float $morning): static
    {
        $this->morning = $morning;

        return $this;
    }

    public function getEveningFirstHour(): ?float
    {
        return $this->eveningFirstHour;
    }

    public function setEveningFirstHour(?float $eveningFirstHour): static
    {
        $this->eveningFirstHour = $eveningFirstHour;

        return $this;
    }

    public function getEveningHalfHour(): ?float
    {
        return $this->eveningHalfHour;
    }

    public function setEveningHalfHour(?float $eveningHalfHour): static
    {
        $this->eveningHalfHour = $eveningHalfHour;

        return $this;
    }
}
