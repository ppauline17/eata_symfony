<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    private ?string $documentSource = null;

    private ?string $old_document = null;

    #[ORM\ManyToMany(targetEntity: Category::class)]
    private Collection $category;

    public function __construct()
    {
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getDocumentSource(): ?string
    {
        return $this->documentSource;
    }

    public function setDocumentSource(string $documentSource): static
    {
        $this->documentSource = $documentSource;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * Get the value of old_document
     *
     * @return ?string
     */
    public function getOldDocument(): ?string
    {
        return $this->old_document;
    }

    /**
     * Set the value of old_document
     *
     * @param ?string $old_document
     *
     * @return self
     */
    public function setOldDocument(?string $old_document): self
    {
        $this->old_document = $old_document;

        return $this;
    }
}
