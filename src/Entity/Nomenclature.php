<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'nomenclatures')]
#[ORM\HasLifecycleCallbacks]
class Nomenclature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'nomenclature_name_required')]
    #[Assert\Length(max: 255, maxMessage: 'nomenclature_name_too_long')]
    private ?string $nomenclatureName = null;

    #[ORM\OneToMany(mappedBy: 'nomenclature', targetEntity: NomenclatureCharacteristicValue::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $nomenclatureCharacteristicValues;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->nomenclatureCharacteristicValues = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomenclatureName(): ?string
    {
        return $this->nomenclatureName;
    }

    public function setNomenclatureName(string $nomenclatureName): static
    {
        $this->nomenclatureName = $nomenclatureName;

        return $this;
    }

    /**
     * @return Collection<int, NomenclatureCharacteristicValue>
     */
    public function getNomenclatureCharacteristicValues(): Collection
    {
        return $this->nomenclatureCharacteristicValues;
    }

    public function addNomenclatureCharacteristicValue(NomenclatureCharacteristicValue $nomenclatureCharacteristicValue): static
    {
        if (!$this->nomenclatureCharacteristicValues->contains($nomenclatureCharacteristicValue)) {
            $this->nomenclatureCharacteristicValues->add($nomenclatureCharacteristicValue);
            $nomenclatureCharacteristicValue->setNomenclature($this);
        }

        return $this;
    }

    public function removeNomenclatureCharacteristicValue(NomenclatureCharacteristicValue $nomenclatureCharacteristicValue): static
    {
        if ($this->nomenclatureCharacteristicValues->removeElement($nomenclatureCharacteristicValue)) {
            if ($nomenclatureCharacteristicValue->getNomenclature() === $this) {
                $nomenclatureCharacteristicValue->setNomenclature(null);
            }
        }

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

    public function getCharacteristics(): array
    {
        $characteristics = [];
        foreach ($this->nomenclatureCharacteristicValues as $ncv) {
            $characteristic = $ncv->getCharacteristic();
            if ($characteristic && !in_array($characteristic, $characteristics, true)) {
                $characteristics[] = $characteristic;
            }
        }
        return $characteristics;
    }

    public function getCharacteristicAvailableValues(): array
    {
        $values = [];
        foreach ($this->nomenclatureCharacteristicValues as $ncv) {
            $value = $ncv->getCharacteristicAvailableValue();
            if ($value) {
                $values[] = $value;
            }
        }
        return $values;
    }

    public function getValuesForCharacteristic(Characteristic $characteristic): array
    {
        $values = [];
        foreach ($this->nomenclatureCharacteristicValues as $ncv) {
            $availableValue = $ncv->getCharacteristicAvailableValue();
            if ($availableValue && $availableValue->getCharacteristic() === $characteristic) {
                $values[] = $availableValue;
            }
        }
        return $values;
    }

    public function hasCharacteristicAvailableValue(CharacteristicAvailableValue $value): bool
    {
        foreach ($this->nomenclatureCharacteristicValues as $ncv) {
            if ($ncv->getCharacteristicAvailableValue() === $value) {
                return true;
            }
        }
        return false;
    }
}
