<?php

namespace App\Repository;

use App\Entity\Property;
use App\Entity\User;
use App\Entity\UserFavorites;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserFavorites>
 */
class UserFavoritesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFavorites::class);
    }

    public function isPropertyFavorite(Property $property, User $user): bool
    {
        return $this->createQueryBuilder('uf')
            ->select('COUNT(uf.id)')
            ->where('uf.propertyId = :property')
            ->andWhere('uf.userId = :user')
            ->setParameter('property', $property)
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    public function findFavoritesWithProperties(User $user): array
    {
        return $this->createQueryBuilder('uf')
            ->select('uf', 'p')
            ->join('uf.propertyId', 'p')
            ->where('uf.userId = :user')
            ->setParameter('user', $user)
            ->orderBy('uf.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return UserFavorites[] Returns an array of UserFavorites objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserFavorites
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
