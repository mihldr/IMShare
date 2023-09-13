<?php

namespace App\Entity;

use App\Repository\UploadRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UploadRepository::class)]
class Upload
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $orig_name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $upload_date = null;

    #[ORM\ManyToOne(inversedBy: 'uploads')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Key $uploaded_by = null;

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

    public function getOrigName(): ?string
    {
        return $this->orig_name;
    }

    public function setOrigName(string $orig_name): static
    {
        $this->orig_name = $orig_name;

        return $this;
    }

    public function getUploadDate(): ?\DateTimeInterface
    {
        return $this->upload_date;
    }

    public function setUploadDate(\DateTimeInterface $upload_date): static
    {
        $this->upload_date = $upload_date;

        return $this;
    }

    public function getUploadedBy(): ?Key
    {
        return $this->uploaded_by;
    }

    public function setUploadedBy(?Key $uploaded_by): static
    {
        $this->uploaded_by = $uploaded_by;

        return $this;
    }
}
