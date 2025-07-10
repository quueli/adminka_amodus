<?php

namespace App\Entity;

use App\Repository\NomenclatureCharacteristicValueRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NomenclatureCharacteristicValueRepository::class)]
#[ORM\Table(name: 'nomenclature_characteristic_value')]
#[ORM\UniqueConstraint(name: 'nomenclature_characteristic_value_unique', columns: ['nomenclature_id', 'characteristic_available_value_id'])]
class NomenclatureCharacteristicValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Nomenclature::class, inversedBy: 'nomenclatureCharacteristicValues')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'nomenclature_required')]
    private ?Nomenclature $nomenclature = null;

    #[ORM\ManyToOne(targetEntity: CharacteristicAvailableValue::class, inversedBy: 'nomenclatureCharacteristicValues')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'characteristic_available_value_required')]
    private ?CharacteristicAvailableValue $characteristicAvailableValue = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomenclature(): ?Nomenclature
    {
        return $this->nomenclature;
    }

    public function setNomenclature(?Nomenclature $nomenclature): static
    {
        $this->nomenclature = $nomenclature;

        return $this;
    }

    public function getCharacteristicAvailableValue(): ?CharacteristicAvailableValue
    {
        return $this->characteristicAvailableValue;
    }

    public function setCharacteristicAvailableValue(?CharacteristicAvailableValue $characteristicAvailableValue): static
    {
        $this->characteristicAvailableValue = $characteristicAvailableValue;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCharacteristic(): ?Characteristic
    {
        return $this->characteristicAvailableValue?->getCharacteristic();
    }

    public function getValue(): ?string
    {
        return $this->characteristicAvailableValue?->getValue();
    }

    public function getCharacteristicName(): ?string
    {
        return $this->characteristicAvailableValue?->getCharacteristic()?->getName();
    }
}
