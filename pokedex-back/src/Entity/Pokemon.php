<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $pokeId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column]
    private array $types = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokeId(): ?int
    {
        return $this->pokeId;
    }

    public function setPokeId(int $pokeId): static
    {
        $this->pokeId = $pokeId;

        return $this;
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getTypes(): array
    {
        return $this->types;
    }

    public function setTypes(array $types): static
    {
        $this->types = $types;

        return $this;
    }
}
