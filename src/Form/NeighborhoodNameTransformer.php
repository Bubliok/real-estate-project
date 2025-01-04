<?php

namespace App\Form;

use App\Entity\Neighborhood;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class NeighborhoodNameTransformer implements  DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function transform($value): string
    {
        if (null === $value) {
            return ' ';
        }

        return $value->getId();
    }

    public function reverseTransform($value): ?Neighborhood
    {
        if (!$value) {
            throw new TransformationFailedException('Neighborhood name cannot be empty.');
        }
        $neighborhood = $this->entityManager
            ->getRepository(Neighborhood::class)
            ->findOneBy(['neighborhoodName' => $value])
        ;

        if (null === $neighborhood) {
            $neighborhood = new Neighborhood();
            $neighborhood->setNeighborhoodName($value);
            $this->entityManager->persist($neighborhood);
            $this->entityManager->flush();
        }

        return $neighborhood;
    }
}