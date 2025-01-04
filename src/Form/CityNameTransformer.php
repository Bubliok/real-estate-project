<?php

namespace App\Form;

use App\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CityNameTransformer implements  DataTransformerInterface
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

    public function reverseTransform($value): ?City
    {
    if (!$value) {
        throw new TransformationFailedException('City name cannot be empty.');
    }
        $city = $this->entityManager
            ->getRepository(City::class)
            ->findOneBy(['cityName' => $value])
        ;

        if (null === $city) {
            $city = new City();
            $city->setCityName($value);
            $this->entityManager->persist($city);
            $this->entityManager->flush();
//            throw new TransformationFailedException(sprintf(
//                'City with name "%s" does not exist!',
//                $value
//            ));
        }

        return $city;
    }
}