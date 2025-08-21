<?php

namespace App\Command;

use App\Entity\Pokemon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:import-pokemons',
    description: 'Import all Pokemons from PokéAPI to local database.',
)]
class ImportPokemonsCommand extends Command
{
    private EntityManagerInterface $em;
    private HttpClientInterface $httpClient;

    public function __construct(EntityManagerInterface $em, HttpClientInterface $httpClient)
    {
        parent::__construct();
        $this->em = $em;
        $this->httpClient = $httpClient;
    }

    protected function configure(): void
    {
        // Pas besoin d’arguments ou options pour l’instant
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->writeln('Début de l’import des Pokémon...');

        // Récupérer les 151 premiers Pokémon
        $response = $this->httpClient->request('GET', 'https://pokeapi.co/api/v2/pokemon?limit=151');
        $data = $response->toArray();

        foreach ($data['results'] as $pokemonSummary) {
            // Récupérer les détails du Pokémon
            $pokeResponse = $this->httpClient->request('GET', $pokemonSummary['url']);
            $pokeData = $pokeResponse->toArray();

            $pokemon = new Pokemon();
            $pokemon->setPokeId($pokeData['id']);
            $pokemon->setName($pokeData['name']);

            // Image officielle
            $imageUrl = $pokeData['sprites']['other']['official-artwork']['front_default'] ?? null;
            $pokemon->setImage($imageUrl);

            // Types en tableau simple
            $types = [];
            foreach ($pokeData['types'] as $type) {
                $types[] = $type['type']['name'];
            }
            $pokemon->setTypes($types);

            $this->em->persist($pokemon);

            $io->writeln("Import du Pokémon : {$pokeData['name']} (ID {$pokeData['id']})");
        }

        $this->em->flush();

        $io->success('Import terminé avec succès !');

        return Command::SUCCESS;
    }
}
