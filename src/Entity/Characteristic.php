<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'characteristics')]
#[ORM\HasLifecycleCallbacks]
class Characteristic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'characteristic_name_required')]
    #[Assert\Length(max: 255, maxMessage: 'characteristic_name_too_long')]
    private ?string $name = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'characteristic', targetEntity: CharacteristicAvailableValue::class, cascade: ['remove'])]
    private Collection $availableValues;

    #[ORM\OneToMany(mappedBy: 'characteristic', targetEntity: Nomenclature::class, cascade: ['remove'])]
    private Collection $nomenclatures;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->availableValues = new ArrayCollection();
        $this->nomenclatures = new ArrayCollection();
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

    public function setUpdatedAt(\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getAvailableValues(): Collection
    {
        return $this->availableValues;
    }

    public function addAvailableValue(CharacteristicAvailableValue $availableValue): static
    {
        if (!$this->availableValues->contains($availableValue)) {
            $this->availableValues->add($availableValue);
            $availableValue->setCharacteristic($this);
        }

        return $this;
    }

    public function removeAvailableValue(CharacteristicAvailableValue $availableValue): static
    {
        if ($this->availableValues->removeElement($availableValue)) {
            if ($availableValue->getCharacteristic() === $this) {
                $availableValue->setCharacteristic(null);
            }
        }

        return $this;
    }

    public function getNomenclatures(): Collection
    {
        return $this->nomenclatures;
    }

    public function addNomenclature(Nomenclature $nomenclature): static
    {
        if (!$this->nomenclatures->contains($nomenclature)) {
            $this->nomenclatures->add($nomenclature);
            $nomenclature->setCharacteristic($this);
        }

        return $this;
    }

    public function removeNomenclature(Nomenclature $nomenclature): static
    {
        if ($this->nomenclatures->removeElement($nomenclature)) {
            if ($nomenclature->getCharacteristic() === $this) {
                $nomenclature->setCharacteristic(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
