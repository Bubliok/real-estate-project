<?php

namespace App\Repository;

use App\Entity\RealEstate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RealEstate>
 */
class RealEstateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RealEstate::class);
    }

public function findByCityIdSortedFurnished(int $cityId, bool $isFurnished, string $sort): array
{
    $qb = $this->createQueryBuilder('r')
        ->where('r.city = :cityId')
        ->andWhere('r.isFurnished = :isFurnished')
        ->setParameter('cityId', $cityId)
        ->setParameter('isFurnished', $isFurnished);

    switch ($sort) {
        case 'price_desc':
            $qb->orderBy('r.estatePrice', 'DESC');
            break;
        case 'area_asc':
            $qb->orderBy('r.estateArea', 'ASC');
            break;
        case 'area_desc':
            $qb->orderBy('r.estateArea', 'DESC');
            break;
        case 'price_asc':
        default:
            $qb->orderBy('r.estatePrice', 'ASC');
            break;
    }

    return $qb->getQuery()->getResult();
}
    public function findByCityId(int $cityId): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.city = :cityId')
            ->setParameter('cityId', $cityId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return RealEstate[]
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('r')
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return RealEstate[] Returns an array of RealEstate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RealEstate
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
