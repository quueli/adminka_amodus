<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'characteristic_available_values')]
#[ORM\HasLifecycleCallbacks]
class CharacteristicAvailableValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Characteristic::class, inversedBy: 'availableValues')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'characteristic_required')]
    private ?Characteristic $characteristic = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'value_required')]
    #[Assert\Length(max: 255, maxMessage: 'value_too_long')]
    private ?string $value = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'characteristicAvailableValue', targetEntity: NomenclatureCharacteristicValue::class, cascade: ['remove'])]
    private Collection $nomenclatureCharacteristicValues;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->nomenclatureCharacteristicValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCharacteristic(): ?Characteristic
    {
        return $this->characteristic;
    }

    public function setCharacteristic(?Characteristic $characteristic): static
    {
        $this->characteristic = $characteristic;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;
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
            $nomenclatureCharacteristicValue->setCharacteristicAvailableValue($this);
        }

        return $this;
    }

    public function removeNomenclatureCharacteristicValue(NomenclatureCharacteristicValue $nomenclatureCharacteristicValue): static
    {
        if ($this->nomenclatureCharacteristicValues->removeElement($nomenclatureCharacteristicValue)) {
            if ($nomenclatureCharacteristicValue->getCharacteristicAvailableValue() === $this) {
                $nomenclatureCharacteristicValue->setCharacteristicAvailableValue(null);
            }
        }

        return $this;
    }

    public function getNomenclatures(): array
    {
        $nomenclatures = [];
        foreach ($this->nomenclatureCharacteristicValues as $ncv) {
            $nomenclature = $ncv->getNomenclature();
            if ($nomenclature && !in_array($nomenclature, $nomenclatures, true)) {
                $nomenclatures[] = $nomenclature;
            }
        }
        return $nomenclatures;
    }

    public function __toString(): string
    {
        return $this->value ?? '';
    }
}
