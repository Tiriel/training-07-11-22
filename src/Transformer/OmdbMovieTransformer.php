<?php

namespace App\Transformer;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;
use Symfony\Component\Form\DataTransformerInterface;

class OmdbMovieTransformer implements DataTransformerInterface
{
    public function __construct(
        private GenreRepository $genreRepository
    ) {}

    public function transform(mixed $value): Movie
    {
        $genreNames = explode(', ', $value['Genre']);
        $date = $value['Released'] === 'N/A' ? $value['Year'] : $value['Released'];
        $movie = (new Movie())
            ->setTitle($value['Title'])
            ->setPoster($value['Poster'])
            ->setCountry($value['Country'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setPrice(5.0)
        ;

        foreach ($genreNames as $name) {
            $entity = $this->genreRepository->findOneBy([ 'name' => $name])
                ?? (new Genre())->setName($name);
            $movie->addGenre($entity);
        }

        return $movie;
    }

    public function reverseTransform(mixed $value)
    {
        throw new \RuntimeException("Method not implemented");
    }
}