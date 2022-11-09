<?php

namespace App\Command;

use App\Consumer\OmdbApiConsumer;
use App\Provider\MovieProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsCommand(
    name: 'app:movie:find',
    description: 'Find a movie by title or Imdb Id',
)]
class MovieFindCommand extends Command
{
    public function __construct(
        private MovieProvider $provider
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('value', InputArgument::OPTIONAL, 'The title or id you wish to search')
            ->addArgument('type', InputArgument::OPTIONAL, 'The type of search (t or i)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->provider->setSymfonyStyle($io);

        if (!$value = $input->getArgument('value')) {
            $value = $io->ask('What is the title or Imdb ID of the movie?');
        }

        $type = $input->getArgument('type');
        if (!\in_array($type, [OmdbApiConsumer::MODE_TITLE, OmdbApiConsumer::MODE_ID])) {
            $type = $io->choice('What is the type of search?', ['t' => 'title', 'i' => 'Imdb Id']);
        }
        $io->title(sprintf("You are searching for a movie with %s \"%s\"", $type, $value));

        try {
            $movie = $this->provider->getMovie($type, $value);
        } catch (NotFoundHttpException $e) {
            $io->error('Movie not found!');

            return Command::FAILURE;
        }

        $io->table(
            ['Id', 'Imdb Id', 'Title', 'Rated'],
            [
                [$movie->getId(), $movie->getImdbId(), $movie->getTitle(), $movie->getRated()]
            ]
        );
        $io->success('Movie found!');

        return Command::SUCCESS;
    }
}
