<?php

namespace App\Provider;

use App\Consumer\OmdbApiConsumer;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Transformer\OmdbMovieTransformer;

class MovieProvider
{
    public function __construct(
        private MovieRepository $repository,
        private OmdbApiConsumer $consumer,
        private OmdbMovieTransformer $transformer
    ){}

    public function getMovie(string $type, string $value): Movie
    {
        //if (!\in_array($type, [OmdbApiConsumer::MODE_ID, OmdbApiConsumer::MODE_TITLE])) {
        if ($type !== OmdbApiConsumer::MODE_TITLE) {
            throw new \InvalidArgumentException();
        }

        $data = $this->consumer->consume($type, $value);
        if ($movie = $this->repository->findOneBy(['title' => $data['Title']])) {
            return $movie;
        }

        $movie = $this->transformer->transform($data);
        $this->repository->save($movie, true);

        return $movie;
    }
}