<?php

namespace App\Entity;

use App\Repository\CatalogItemRepository;
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
    private ?string $baseType = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $item = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mainItem = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $itemName = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $layer = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $warmSummer = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $coolSummerWarmSpringAutumn = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $coolSpringAutumnWarmWinter = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $coldWinter = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $outerWarmSummer = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $outerCoolSummerWarmSpringAutumn = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $outerCoolSpringAutumnWarmWinter = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    private ?bool $outerColdWinter = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $synonym = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $constructionDetails = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $sortOrder = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
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

    public function getWarmSummer(): ?bool
    {
        return $this->warmSummer;
    }

    public function setWarmSummer(?bool $warmSummer): static
    {
        $this->warmSummer = $warmSummer;
        return $this;
    }

    public function getCoolSummerWarmSpringAutumn(): ?bool
    {
        return $this->coolSummerWarmSpringAutumn;
    }

    public function setCoolSummerWarmSpringAutumn(?bool $coolSummerWarmSpringAutumn): static
    {
        $this->coolSummerWarmSpringAutumn = $coolSummerWarmSpringAutumn;
        return $this;
    }

    public function getCoolSpringAutumnWarmWinter(): ?bool
    {
        return $this->coolSpringAutumnWarmWinter;
    }

    public function setCoolSpringAutumnWarmWinter(?bool $coolSpringAutumnWarmWinter): static
    {
        $this->coolSpringAutumnWarmWinter = $coolSpringAutumnWarmWinter;
        return $this;
    }

    public function getColdWinter(): ?bool
    {
        return $this->coldWinter;
    }

    public function setColdWinter(?bool $coldWinter): static
    {
        $this->coldWinter = $coldWinter;
        return $this;
    }

    public function getOuterWarmSummer(): ?bool
    {
        return $this->outerWarmSummer;
    }

    public function setOuterWarmSummer(?bool $outerWarmSummer): static
    {
        $this->outerWarmSummer = $outerWarmSummer;
        return $this;
    }

    public function getOuterCoolSummerWarmSpringAutumn(): ?bool
    {
        return $this->outerCoolSummerWarmSpringAutumn;
    }

    public function setOuterCoolSummerWarmSpringAutumn(?bool $outerCoolSummerWarmSpringAutumn): static
    {
        $this->outerCoolSummerWarmSpringAutumn = $outerCoolSummerWarmSpringAutumn;
        return $this;
    }

    public function getOuterCoolSpringAutumnWarmWinter(): ?bool
    {
        return $this->outerCoolSpringAutumnWarmWinter;
    }

    public function setOuterCoolSpringAutumnWarmWinter(?bool $outerCoolSpringAutumnWarmWinter): static
    {
        $this->outerCoolSpringAutumnWarmWinter = $outerCoolSpringAutumnWarmWinter;
        return $this;
    }

    public function getOuterColdWinter(): ?bool
    {
        return $this->outerColdWinter;
    }

    public function setOuterColdWinter(?bool $outerColdWinter): static
    {
        $this->outerColdWinter = $outerColdWinter;
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

    public function getHierarchyLevel(): int
    {
        if ($this->baseType && !$this->item) return 0;
        if ($this->item && !$this->location) return 1;
        if ($this->location && !$this->mainItem) return 2;
        if ($this->mainItem && !$this->itemName) return 3;
        if ($this->itemName) return 4;
        return 0;
    }

    public function isMainClothing(): bool
    {
        return $this->item === 'Основная одежда';
    }

    public function isOuterClothing(): bool
    {
        return $this->item === 'Верхняя одежда';
    }

    public function getApplicableSeasons(): array
    {
        $seasons = [];

        if ($this->isMainClothing()) {
            if ($this->warmSummer) $seasons[] = 'warm_summer';
            if ($this->coolSummerWarmSpringAutumn) $seasons[] = 'cool_summer_warm_spring_autumn';
            if ($this->coolSpringAutumnWarmWinter) $seasons[] = 'cool_spring_autumn_warm_winter';
            if ($this->coldWinter) $seasons[] = 'cold_winter';
        } elseif ($this->isOuterClothing()) {
            if ($this->outerWarmSummer) $seasons[] = 'warm_summer';
            if ($this->outerCoolSummerWarmSpringAutumn) $seasons[] = 'cool_summer_warm_spring_autumn';
            if ($this->outerCoolSpringAutumnWarmWinter) $seasons[] = 'cool_spring_autumn_warm_winter';
            if ($this->outerColdWinter) $seasons[] = 'cold_winter';
        }

        return $seasons;
    }

    public function getHierarchyPath(): string
    {
        $parts = array_filter([
            $this->baseType,
            $this->item,
            $this->location,
            $this->mainItem,
            $this->itemName
        ]);

        return implode(' > ', $parts);
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
