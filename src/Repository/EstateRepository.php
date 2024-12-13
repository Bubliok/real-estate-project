<?php

namespace App\Repository;

use App\Model\Estate;
use Psr\Log\LoggerInterface;

class EstateRepository
{

    public function __construct(private LoggerInterface $logger)
    {

    }
    public function findAll(): array
    {
        $this->logger->info('Fetching real estate data');
        return [
            new Estate(
                1,
                'Beautiful 3 Bedroom Apartment',
                150000,
                'A stunning 3 bedroom apartment located in the heart of the city.'
            ),
            new Estate(
                2,
                'Luxurious 5 Bedroom House',
                500000,
                'A luxurious 5 bedroom house with a swimming pool and a large garden.'
            ),
            new Estate(
                3,
                'Cozy 2 Bedroom Cottage',
                100000,
                'A cozy 2 bedroom cottage located in a quiet village.'
            ),
        ];
    }
        public function find(int $id): ?Estate{

            foreach($this->findAll() as $estate){
                if($estate->getId() === $id){
                    return $estate;
                }
            }
            return null;


    }
}