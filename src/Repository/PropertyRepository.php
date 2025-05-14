<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Property>
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    public function getByCityAndListingType(int $cityId, string $listingType, ?array $residentialTypes = null, ?array $commercialTypes = null, ?array $landTypes = null): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.listingType = :listingType')
            ->andWhere('p.cityId = :cityId')
            ->setParameter('listingType', $listingType)
            ->setParameter('cityId', $cityId);

        $hasFilters = false;
        $conditions = [];

        if ($residentialTypes && !empty($residentialTypes)) {
            $qb->leftJoin('p.residential', 'r');
            $conditions[] = 'r.residentialType IN (:residentialTypes)';
            $qb->setParameter('residentialTypes', $residentialTypes);
            $hasFilters = true;
        }

        if ($commercialTypes && !empty($commercialTypes)) {
            $qb->leftJoin('p.commercial', 'c');
            $conditions[] = 'c.commercialType IN (:commercialTypes)';
            $qb->setParameter('commercialTypes', $commercialTypes);
            $hasFilters = true;
        }

        if ($landTypes && !empty($landTypes)) {
            $qb->leftJoin('p.land', 'l');
            $conditions[] = 'l.zoningType IN (:landTypes)';
            $qb->setParameter('landTypes', $landTypes);
            $hasFilters = true;
        }

        if ($hasFilters && !empty($conditions)) {
            $qb->andWhere('(' . implode(' OR ', $conditions) . ')');
        }

        return $qb->getQuery()->getResult();
    }

    public function getAll() {
        $qb = $this->createQueryBuilder('p');
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Property[] Returns an array of Property objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Property
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
