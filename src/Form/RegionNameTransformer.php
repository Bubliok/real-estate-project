<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class RegionNameTransformer implements DataTransformerInterface
{
    private $logger;
    private $cityIdFromForm = null;

    public function __construct(
        private EntityManagerInterface $entityManager,
        ?LoggerInterface $logger = null,
        private ?RequestStack $requestStack = null
    ) {
        $this->logger = $logger;
    }

    /**
     * Set the cityId to use when creating a new Region
     */
    public function setCityId(?City $cityId): void
    {
        $this->cityIdFromForm = $cityId;
        $this->log("City ID set for region transformer: " . ($cityId ? $cityId->getName() : 'null'));
    }

    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        if ($value instanceof Region) {
            $this->log("Transforming Region entity to string: " . $value->getName());
            return $value->getName();
        }

        $this->log("Invalid value for transform in RegionNameTransformer: " . gettype($value));
        return '';
    }

    public function reverseTransform($value): ?Region
    {
        $this->log("Reverse transforming value: " . ($value ?? 'null'));
        
        if (!$value) {
            return null;  // Allow empty regions
        }
        
        $region = $this->entityManager
            ->getRepository(Region::class)
            ->findOneBy(['name' => $value]);

        if (null === $region) {
            $this->log("Creating new Region with name: " . $value);
            $region = new Region();
            $region->setName($value);
            
            // Set the city ID if available
            if ($this->cityIdFromForm) {
                $region->setCityId($this->cityIdFromForm);
                $this->log("Set city ID for new region: " . $this->cityIdFromForm->getName());
            } else {
                $this->log("WARNING: Creating region without city_id - this may violate database constraints");
            }
            
            $this->entityManager->persist($region);
        } else {
            $this->log("Found existing Region: " . $region->getName());
        }

        return $region;
    }
    
    private function log(string $message): void
    {
        if ($this->logger) {
            $this->logger->info('[RegionNameTransformer] ' . $message);
        }
    }
} 