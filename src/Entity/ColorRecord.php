<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'colors')]
class ColorRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'color_required')]
    #[Assert\Length(max: 255, maxMessage: 'color_too_long')]
    private ?string $color = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'hex_color_required')]
    #[Assert\Length(max: 255, maxMessage: 'hex_color_too_long')]
    private ?string $hexColorNumber = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'saturation_required')]
    #[Assert\Length(max: 255, maxMessage: 'saturation_too_long')]
    private ?string $saturation = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'palette_required')]
    #[Assert\Length(max: 255, maxMessage: 'palette_too_long')]
    private ?string $palette = null;

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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;
        return $this;
    }

    public function getHexColorNumber(): ?string
    {
        return $this->hexColorNumber;
    }

    public function setHexColorNumber(string $hexColorNumber): static
    {
        $this->hexColorNumber = $hexColorNumber;
        return $this;
    }

    public function getSaturation(): ?string
    {
        return $this->saturation;
    }

    public function setSaturation(string $saturation): static
    {
        $this->saturation = $saturation;
        return $this;
    }

    public function getPalette(): ?string
    {
        return $this->palette;
    }

    public function setPalette(string $palette): static
    {
        $this->palette = $palette;
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
