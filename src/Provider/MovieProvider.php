<?php

namespace App\Provider;

use App\Consumer\OmdbApiConsumer;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Transformer\OmdbMovieTransformer;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieProvider
{
    private ?SymfonyStyle $io = null;

    public function __construct(
        private MovieRepository $repository,
        private OmdbApiConsumer $consumer,
        private OmdbMovieTransformer $transformer
    ){}

    public function getMovie(string $type, string $value): Movie
    {
        if (!\in_array($type, [OmdbApiConsumer::MODE_ID, OmdbApiConsumer::MODE_TITLE])) {
            throw new \InvalidArgumentException();
        }

        $this->io?->text('Calling OMDb API.');
        $data = $this->consumer->consume($type, $value);
        if ($movie = $this->repository->findOneBy(['title' => $data['Title']])) {
            $this->io?->note('Movie found in database.');
            return $movie;
        }

        $this->io?->section('Movie not found in database, saving.');
        $movie = $this->transformer->transform($data);
        $this->repository->save($movie, true);
        $this->io?->note('Movie saved!');

        return $movie;
    }

    public function setSymfonyStyle(SymfonyStyle $io): void
    {
        $this->io = $io;
    }
}