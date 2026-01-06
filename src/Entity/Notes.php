<?php

namespace App\Entity;

use App\Repository\NotesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: NotesRepository::class)]
class Notes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $noteName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $noteDescription = null;

    #[ORM\ManyToOne(User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoteName(): ?string
    {
        return $this->noteName;
    }

    public function setNoteName(string $noteName): static
    {
        $this->noteName = $noteName;

        return $this;
    }

    public function getNoteDescription(): ?string
    {
        return $this->noteDescription;
    }

    public function setNoteDescription(string $noteDescription): static
    {
        $this->noteDescription = $noteDescription;

        return $this;
    }
    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(?int $user): static
    {
        $this->user = $user;

        return $this;
    }
}
