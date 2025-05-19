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

    public function getByCityAndListingType(int $cityId, string $listingType, array $filters): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.listingType = :listingType')
            ->andWhere('p.cityId = :cityId')
            ->setParameter('listingType', $listingType)
            ->setParameter('cityId', $cityId);

        $propertyType = $filters['propertyType'] ?? 'all';

        switch ($propertyType) {
            case 'residential':
                $this->applyResidentialFilters($qb, $filters);
                break;
            case 'commercial':
                $this->applyCommercialFilters($qb, $filters);
                break;
            case 'land':
                $this->applyLandFilters($qb, $filters);
                break;
            case 'all':
            default:
                $qb->leftJoin('p.residential', 'r')
                   ->leftJoin('p.commercial', 'c')
                   ->leftJoin('p.land', 'l');
                break;
        }
        
        if (isset($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $qb->orderBy('p.price', 'ASC');
                    break;
                case 'price_desc':
                    $qb->orderBy('p.price', 'DESC');
                    break;
                case 'area_asc':
                    $qb->orderBy('p.area', 'ASC');
                    break;
                case 'area_desc':
                    $qb->orderBy('p.area', 'DESC');
                    break;
                default:
                    $qb->orderBy('p.createdAt', 'DESC');
                    break;
            }
        } else {
            $qb->orderBy('p.createdAt', 'DESC');
        }

        return $qb->getQuery()->getResult();
    }

    public function getByCityAndResidentialType(int $cityId, string $listingType, array $filters = []): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.listingType = :listingType')
            ->andWhere('p.cityId = :cityId')
            ->setParameter('listingType', 'residential')
            ->setParameter('cityId', $cityId);

        $this->applyResidentialFilters($qb, $filters);

        return $qb->getQuery()->getResult();
    }

    public function getByCityAndCommercialType(int $cityId, array $filters = []): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.listingType = :listingType')
            ->andWhere('p.cityId = :cityId')
            ->setParameter('listingType', 'commercial')
            ->setParameter('cityId', $cityId);

        $this->applyCommercialFilters($qb, $filters);

        return $qb->getQuery()->getResult();
    }

    public function getByCityAndLandType(int $cityId, array $filters = []): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.listingType = :listingType')
            ->andWhere('p.cityId = :cityId')
            ->setParameter('listingType', 'land')
            ->setParameter('cityId', $cityId);

        $this->applyLandFilters($qb, $filters);

        return $qb->getQuery()->getResult();
    }

    private function applyResidentialFilters($qb, array $filters): void
    {
        $qb->leftJoin('p.residential', 'r')
           ->andWhere('r IS NOT NULL');

        if (!empty($filters['residentialTypes'])) {
            $qb->andWhere('r.residentialType IN (:residentialTypes)')
                ->setParameter('residentialTypes', $filters['residentialTypes']);
        }

        if (!empty($filters['bedrooms'])) {
            $qb->andWhere('r.bedrooms >= :bedrooms')
                ->setParameter('bedrooms', $filters['bedrooms']);
        }

        if (!empty($filters['bathrooms'])) {
            $qb->andWhere('r.bathrooms >= :bathrooms')
                ->setParameter('bathrooms', $filters['bathrooms']);
        }

        if (!empty($filters['features'])) {
            if (in_array('parking', $filters['features'])) {
                $qb->andWhere('r.hasParking = true');
            }
            if (in_array('furnished', $filters['features'])) {
                $qb->andWhere('r.isFurnished = true');
            }
        }
    }

    private function applyCommercialFilters($qb, array $filters): void
    {
        $qb->leftJoin('p.commercial', 'c')
           ->andWhere('c IS NOT NULL');

        if (!empty($filters['commercialTypes'])) {
            $qb->andWhere('c.commercialType IN (:commercialTypes)')
                ->setParameter('commercialTypes', $filters['commercialTypes']);
        }

        if (!empty($filters['commercialFeatures'])) {
            if (in_array('parking', $filters['commercialFeatures'])) {
                $qb->andWhere('c.hasParking = true');
            }
            if (in_array('furnished', $filters['commercialFeatures'])) {
                $qb->andWhere('c.isFurnished = true');
            }
        }
    }

    private function applyLandFilters($qb, array $filters): void
    {
        $qb->leftJoin('p.land', 'l')
           ->andWhere('l IS NOT NULL');

        if (!empty($filters['landTypes'])) {
            $qb->andWhere('l.zoningType IN (:landTypes)')
                ->setParameter('landTypes', $filters['landTypes']);
        }

        if (!empty($filters['landFeatures'])) {
            if (in_array('electricity', $filters['landFeatures'])) {
                $qb->andWhere('l.hasElectricity = true');
            }
            if (in_array('water', $filters['landFeatures'])) {
                $qb->andWhere('l.hasWater = true');
            }
        }
    }

    public function getAll()
    {
        $qb = $this->createQueryBuilder('p');
        return $qb->getQuery()->getResult();
    }

    public function findBySlug(string $slug): ?Property
    {
        return $this->createQueryBuilder('p')
            ->where('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Count properties in a list of cities
     *
     * @param array $cityIds Array of city IDs
     * @return int The number of properties
     */
    public function countByCityIds(array $cityIds): int
    {
        if (empty($cityIds)) {
            return 0;
        }
        
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.cityId IN (:cityIds)')
            ->setParameter('cityIds', $cityIds)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Calls the stored procedure to count properties by city and listing type.
     */
    public function countByCityAndTypeSP(int $cityId, string $listingType): int
    {
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare('SELECT countpropertiesbycityandtype(:cityId, :listingType)');
        $stmt->bindValue('cityId', $cityId);
        $stmt->bindValue('listingType', $listingType);
        $result = $stmt->executeQuery()->fetchNumeric();
        return (int) ($result ? $result[0] : 0);
    }

    /**
     * Calls the stored procedure to get filtered properties (example: by city, type, min bedrooms, min bathrooms).
     */
    public function getFilteredPropertiesSP(int $cityId, string $listingType, ?int $minBedrooms = null, ?int $minBathrooms = null): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare('SELECT * FROM getfilteredproperties(:cityId, :listingType, :minBedrooms, :minBathrooms)');
        $stmt->bindValue('cityId', $cityId);
        $stmt->bindValue('listingType', $listingType);
        $stmt->bindValue('minBedrooms', $minBedrooms);
        $stmt->bindValue('minBathrooms', $minBathrooms);
        return $stmt->executeQuery()->fetchAllAssociative();
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
