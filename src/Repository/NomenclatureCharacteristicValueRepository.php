<?php

namespace App\Repository;

use App\Entity\NomenclatureCharacteristicValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class NomenclatureCharacteristicValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NomenclatureCharacteristicValue::class);
    }

    public function findByNomenclature($nomenclature): array
    {
        return $this->createQueryBuilder('ncv')
            ->andWhere('ncv.nomenclature = :nomenclature')
            ->setParameter('nomenclature', $nomenclature)
            ->orderBy('ncv.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCharacteristicAvailableValue($characteristicAvailableValue): array
    {
        return $this->createQueryBuilder('ncv')
            ->andWhere('ncv.characteristicAvailableValue = :characteristicAvailableValue')
            ->setParameter('characteristicAvailableValue', $characteristicAvailableValue)
            ->orderBy('ncv.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function existsConnection($nomenclature, $characteristicAvailableValue): bool
    {
        $result = $this->createQueryBuilder('ncv')
            ->select('COUNT(ncv.id)')
            ->andWhere('ncv.nomenclature = :nomenclature')
            ->andWhere('ncv.characteristicAvailableValue = :characteristicAvailableValue')
            ->setParameter('nomenclature', $nomenclature)
            ->setParameter('characteristicAvailableValue', $characteristicAvailableValue)
            ->getQuery()
            ->getSingleScalarResult();

        return $result > 0;
    }

    public function removeAllForNomenclature($nomenclature): void
    {
        $this->createQueryBuilder('ncv')
            ->delete()
            ->andWhere('ncv.nomenclature = :nomenclature')
            ->setParameter('nomenclature', $nomenclature)
            ->getQuery()
            ->execute();
    }

    public function updateNomenclatureConnections($nomenclature, array $characteristicAvailableValueIds): void
    {
        $entityManager = $this->getEntityManager();

        $this->removeAllForNomenclature($nomenclature);

        $entityManager->flush();

        foreach (array_unique($characteristicAvailableValueIds) as $valueId) {
            $availableValue = $entityManager
                ->getRepository(\App\Entity\CharacteristicAvailableValue::class)
                ->find($valueId);

            if ($availableValue && !$this->existsConnection($nomenclature, $availableValue)) {
                $connection = new \App\Entity\NomenclatureCharacteristicValue();
                $connection->setNomenclature($nomenclature);
                $connection->setCharacteristicAvailableValue($availableValue);

                $entityManager->persist($connection);
            }
        }

        $entityManager->flush();
    }
}
