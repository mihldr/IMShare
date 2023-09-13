<?php

namespace App\Entity;

use App\Repository\KeyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KeyRepository::class)]
#[ORM\Table(name: '`key`')]
class Key
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $secret = null;

    #[ORM\Column]
    private ?bool $is_active = null;

    #[ORM\ManyToOne(inversedBy: 'secrets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $generated_by = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $generated_at = null;

    #[ORM\Column]
    private ?bool $allow_images = null;

    #[ORM\Column]
    private ?bool $allow_videos = null;

    #[ORM\Column]
    private ?bool $allow_documents = null;

    #[ORM\Column]
    private ?bool $allow_executables = null;

    #[ORM\OneToMany(mappedBy: 'uploaded_by', targetEntity: Upload::class)]
    private Collection $uploads;

    public function __construct()
    {
        $this->uploads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): static
    {
        $this->secret = $secret;

        return $this;
    }

    public function getGeneratedBy(): ?User
    {
        return $this->generated_by;
    }

    public function setGeneratedBy(?User $generated_by): static
    {
        $this->generated_by = $generated_by;

        return $this;
    }

    public function getGeneratedAt(): ?\DateTimeInterface
    {
        return $this->generated_at;
    }

    public function setGeneratedAt(\DateTimeInterface $generated_at): static
    {
        $this->generated_at = $generated_at;

        return $this;
    }

    public function isAllowImages(): ?bool
    {
        return $this->allow_images;
    }

    public function setAllowImages(bool $allow_images): static
    {
        $this->allow_images = $allow_images;

        return $this;
    }

    public function isAllowVideos(): ?bool
    {
        return $this->allow_videos;
    }

    public function setAllowVideos(bool $allow_videos): static
    {
        $this->allow_videos = $allow_videos;

        return $this;
    }

    public function isAllowDocuments(): ?bool
    {
        return $this->allow_documents;
    }

    public function setAllowDocuments(bool $allow_documents): static
    {
        $this->allow_documents = $allow_documents;

        return $this;
    }

    public function isAllowExecutables(): ?bool
    {
        return $this->allow_executables;
    }

    public function setAllowExecutables(bool $allow_executables): static
    {
        $this->allow_executables = $allow_executables;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function toArray(): array {
        return [
            "is_active" => $this->is_active,
            "secret" => $this->secret,
            "allow_images" => $this->allow_images,
            "allow_documents" => $this->allow_documents,
            "allow_videos" => $this->allow_videos,
            "allow_executables" => $this->allow_executables,
            "generated_at" => $this->generated_at->getTimestamp(),
            "generated_by" => $this->getGeneratedBy()->getUsername()
        ];
    }

    /**
     * @return Collection<int, Upload>
     */
    public function getUploads(): Collection
    {
        return $this->uploads;
    }

    public function addUpload(Upload $upload): static
    {
        if (!$this->uploads->contains($upload)) {
            $this->uploads->add($upload);
            $upload->setUploadedBy($this);
        }

        return $this;
    }

    public function removeUpload(Upload $upload): static
    {
        if ($this->uploads->removeElement($upload)) {
            // set the owning side to null (unless already changed)
            if ($upload->getUploadedBy() === $this) {
                $upload->setUploadedBy(null);
            }
        }

        return $this;
    }
}
