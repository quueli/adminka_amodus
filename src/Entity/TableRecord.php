<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'table_records')]
class TableRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Field 1 cannot be blank')]
    #[Assert\Length(max: 255, maxMessage: 'Field 1 cannot be longer than {{ limit }} characters')]
    private ?string $field1 = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Field 2 cannot be blank')]
    #[Assert\Length(max: 255, maxMessage: 'Field 2 cannot be longer than {{ limit }} characters')]
    private ?string $field2 = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'Field 3 cannot be longer than {{ limit }} characters')]
    private ?string $field3 = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'Field 4 cannot be longer than {{ limit }} characters')]
    private ?string $field4 = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'Field 5 cannot be longer than {{ limit }} characters')]
    private ?string $field5 = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getField1(): ?string
    {
        return $this->field1;
    }

    public function setField1(string $field1): static
    {
        $this->field1 = $field1;
        return $this;
    }

    public function getField2(): ?string
    {
        return $this->field2;
    }

    public function setField2(string $field2): static
    {
        $this->field2 = $field2;
        return $this;
    }

    public function getField3(): ?string
    {
        return $this->field3;
    }

    public function setField3(?string $field3): static
    {
        $this->field3 = $field3;
        return $this;
    }

    public function getField4(): ?string
    {
        return $this->field4;
    }

    public function setField4(?string $field4): static
    {
        $this->field4 = $field4;
        return $this;
    }

    public function getField5(): ?string
    {
        return $this->field5;
    }

    public function setField5(?string $field5): static
    {
        $this->field5 = $field5;
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
}
