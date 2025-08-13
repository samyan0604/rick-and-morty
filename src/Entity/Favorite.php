<?php

namespace App\Entity;

use App\Repository\FavoriteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteRepository::class)]
class Favorite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'favorites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $characterId = null;

    #[ORM\Column(length: 255)]
    private ?string $characterName = null;

    #[ORM\Column(length: 500)]
    private ?string $characterImage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCharacterId(): ?int
    {
        return $this->characterId;
    }

    public function setCharacterId(int $characterId): static
    {
        $this->characterId = $characterId;

        return $this;
    }

    public function getCharacterName(): ?string
    {
        return $this->characterName;
    }

    public function setCharacterName(string $characterName): static
    {
        $this->characterName = $characterName;

        return $this;
    }

    public function getCharacterImage(): ?string
    {
        return $this->characterImage;
    }

    public function setCharacterImage(string $characterImage): static
    {
        $this->characterImage = $characterImage;

        return $this;
    }
}
