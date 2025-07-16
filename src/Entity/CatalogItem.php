<?php

namespace App\Entity;

use App\Repository\CatalogItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CatalogItemRepository::class)]
#[ORM\Table(name: 'catalog_items')]
class CatalogItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Название не может быть пустым')]
    #[Assert\Length(max: 255, maxMessage: 'Название не может быть длиннее {{ limit }} символов')]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $baseType = null; // Тип базы

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $item = null; // Вещь

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null; // Место расположения

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mainItem = null; // Главный предмет

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $itemName = null; // Предмет

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $layer = null; // Слой

    // Seasonal columns (8 seasons)
    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $season1 = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $season2 = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $season3 = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $season4 = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $season5 = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $season6 = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $season7 = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $season8 = null;

    // Additional fields as requested
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $synonym = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $constructionDetails = null;

    // Adjacency list fields for tree structure
    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class, cascade: ['persist', 'remove'])]
    private Collection $children;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $sortOrder = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

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

    public function getBaseType(): ?string
    {
        return $this->baseType;
    }

    public function setBaseType(?string $baseType): static
    {
        $this->baseType = $baseType;
        return $this;
    }

    public function getItem(): ?string
    {
        return $this->item;
    }

    public function setItem(?string $item): static
    {
        $this->item = $item;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;
        return $this;
    }

    public function getMainItem(): ?string
    {
        return $this->mainItem;
    }

    public function setMainItem(?string $mainItem): static
    {
        $this->mainItem = $mainItem;
        return $this;
    }

    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    public function setItemName(?string $itemName): static
    {
        $this->itemName = $itemName;
        return $this;
    }

    public function getLayer(): ?string
    {
        return $this->layer;
    }

    public function setLayer(?string $layer): static
    {
        $this->layer = $layer;
        return $this;
    }

    // Season getters and setters
    public function getSeason1(): ?bool
    {
        return $this->season1;
    }

    public function setSeason1(?bool $season1): static
    {
        $this->season1 = $season1;
        return $this;
    }

    public function getSeason2(): ?bool
    {
        return $this->season2;
    }

    public function setSeason2(?bool $season2): static
    {
        $this->season2 = $season2;
        return $this;
    }

    public function getSeason3(): ?bool
    {
        return $this->season3;
    }

    public function setSeason3(?bool $season3): static
    {
        $this->season3 = $season3;
        return $this;
    }

    public function getSeason4(): ?bool
    {
        return $this->season4;
    }

    public function setSeason4(?bool $season4): static
    {
        $this->season4 = $season4;
        return $this;
    }

    public function getSeason5(): ?bool
    {
        return $this->season5;
    }

    public function setSeason5(?bool $season5): static
    {
        $this->season5 = $season5;
        return $this;
    }

    public function getSeason6(): ?bool
    {
        return $this->season6;
    }

    public function setSeason6(?bool $season6): static
    {
        $this->season6 = $season6;
        return $this;
    }

    public function getSeason7(): ?bool
    {
        return $this->season7;
    }

    public function setSeason7(?bool $season7): static
    {
        $this->season7 = $season7;
        return $this;
    }

    public function getSeason8(): ?bool
    {
        return $this->season8;
    }

    public function setSeason8(?bool $season8): static
    {
        $this->season8 = $season8;
        return $this;
    }

    public function getSynonym(): ?string
    {
        return $this->synonym;
    }

    public function setSynonym(?string $synonym): static
    {
        $this->synonym = $synonym;
        return $this;
    }

    public function getConstructionDetails(): ?string
    {
        return $this->constructionDetails;
    }

    public function setConstructionDetails(?string $constructionDetails): static
    {
        $this->constructionDetails = $constructionDetails;
        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): static
    {
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            $child->setParent($this);
        }
        return $this;
    }

    public function removeChild(self $child): static
    {
        if ($this->children->removeElement($child)) {
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }
        return $this;
    }

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(?int $sortOrder): static
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    // Helper methods for tree operations
    public function getLevel(): int
    {
        $level = 0;
        $parent = $this->parent;
        while ($parent !== null) {
            $level++;
            $parent = $parent->getParent();
        }
        return $level;
    }

    public function isRoot(): bool
    {
        return $this->parent === null;
    }

    public function hasChildren(): bool
    {
        return !$this->children->isEmpty();
    }

    public function getPath(): array
    {
        $path = [];
        $current = $this;
        while ($current !== null) {
            array_unshift($path, $current);
            $current = $current->getParent();
        }
        return $path;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
