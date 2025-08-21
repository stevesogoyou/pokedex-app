<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Pokemon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class PokemonController extends AbstractController
{
     private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/api/pokemons', name: 'pokemon_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $pokemons = $this->em->getRepository(Pokemon::class)->findAll();

        $data = [];

        foreach ($pokemons as $pokemon) {
            $data[] = [
                'pokeId' => $pokemon->getPokeId(),
                'name' => $pokemon->getName(),
                'image' => $pokemon->getImage(),
                'types' => $pokemon->getTypes(),
            ];
        }

        return new JsonResponse($data);
    }






}
