<?php

namespace App\Repository;

use App\Entity\CatalogItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CatalogItem>
 *
 * @method CatalogItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method CatalogItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method CatalogItem[]    findAll()
 * @method CatalogItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatalogItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CatalogItem::class);
    }

    public function findAllSorted(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.baseType', 'ASC')
            ->addOrderBy('c.item', 'ASC')
            ->addOrderBy('c.location', 'ASC')
            ->addOrderBy('c.mainItem', 'ASC')
            ->addOrderBy('c.itemName', 'ASC')
            ->addOrderBy('c.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function buildHierarchicalTree(): array
    {
        $items = $this->findAllSorted();
        $tree = [];

        foreach ($items as $item) {
            $this->addToTree($tree, $item);
        }

        return $tree;
    }

    private function addToTree(array &$tree, CatalogItem $item): void
    {
        $baseType = $item->getBaseType() ?: 'Без категории';
        $itemType = $item->getItem();
        $location = $item->getLocation();
        $mainItem = $item->getMainItem();
        $itemName = $item->getItemName();

        if (!isset($tree[$baseType])) {
            $tree[$baseType] = [
                'name' => $baseType,
                'level' => 0,
                'children' => [],
                'items' => []
            ];
        }

        $current = &$tree[$baseType];

        if ($itemType) {
            if (!isset($current['children'][$itemType])) {
                $current['children'][$itemType] = [
                    'name' => $itemType,
                    'level' => 1,
                    'children' => [],
                    'items' => []
                ];
            }
            $current = &$current['children'][$itemType];

            if ($location) {
                if (!isset($current['children'][$location])) {
                    $current['children'][$location] = [
                        'name' => $location,
                        'level' => 2,
                        'children' => [],
                        'items' => []
                    ];
                }
                $current = &$current['children'][$location];

                if ($mainItem) {
                    if (!isset($current['children'][$mainItem])) {
                        $current['children'][$mainItem] = [
                            'name' => $mainItem,
                            'level' => 3,
                            'children' => [],
                            'items' => []
                        ];
                    }
                    $current = &$current['children'][$mainItem];

                    if ($itemName) {
                        if (!isset($current['children'][$itemName])) {
                            $current['children'][$itemName] = [
                                'name' => $itemName,
                                'level' => 4,
                                'children' => [],
                                'items' => []
                            ];
                        }
                        $current = &$current['children'][$itemName];
                    }
                }
            }
        }

        // Добавляем сам элемент в соответствующий узел
        $current['items'][] = $item;
    }

    public function findByHierarchyLevel(int $level): array
    {
        $qb = $this->createQueryBuilder('c');

        switch ($level) {
            case 0: // Корневой уровень - только baseType
                $qb->where('c.baseType IS NOT NULL')
                   ->andWhere('c.item IS NULL');
                break;
            case 1: // Уровень 1 - baseType + item
                $qb->where('c.baseType IS NOT NULL')
                   ->andWhere('c.item IS NOT NULL')
                   ->andWhere('c.location IS NULL');
                break;
            case 2: // Уровень 2 - baseType + item + location
                $qb->where('c.baseType IS NOT NULL')
                   ->andWhere('c.item IS NOT NULL')
                   ->andWhere('c.location IS NOT NULL')
                   ->andWhere('c.mainItem IS NULL');
                break;
            case 3: // Уровень 3 - baseType + item + location + mainItem
                $qb->where('c.baseType IS NOT NULL')
                   ->andWhere('c.item IS NOT NULL')
                   ->andWhere('c.location IS NOT NULL')
                   ->andWhere('c.mainItem IS NOT NULL')
                   ->andWhere('c.itemName IS NULL');
                break;
            case 4: // Уровень 4 - все поля заполнены
                $qb->where('c.baseType IS NOT NULL')
                   ->andWhere('c.item IS NOT NULL')
                   ->andWhere('c.location IS NOT NULL')
                   ->andWhere('c.mainItem IS NOT NULL')
                   ->andWhere('c.itemName IS NOT NULL');
                break;
        }

        return $qb->orderBy('c.sortOrder', 'ASC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByNameLike(string $searchTerm): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.name LIKE :searchTerm')
            ->orWhere('c.synonym LIKE :searchTerm')
            ->orWhere('c.itemName LIKE :searchTerm')
            ->orWhere('c.mainItem LIKE :searchTerm')
            ->orWhere('c.baseType LIKE :searchTerm')
            ->orWhere('c.item LIKE :searchTerm')
            ->orWhere('c.location LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByClothingType(string $clothingType): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.item = :clothingType')
            ->setParameter('clothingType', $clothingType)
            ->orderBy('c.location', 'ASC')
            ->addOrderBy('c.mainItem', 'ASC')
            ->addOrderBy('c.itemName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findMainClothing(): array
    {
        return $this->findByClothingType('Основная одежда');
    }

    public function findOuterClothing(): array
    {
        return $this->findByClothingType('Верхняя одежда');
    }

    public function findBySeasons(array $seasons, ?string $clothingType = null): array
    {
        $qb = $this->createQueryBuilder('c');
        $conditions = [];

        // Определяем, какие поля сезонов использовать
        $isOuterClothing = $clothingType === 'Верхняя одежда';
        $prefix = $isOuterClothing ? 'outer' : '';

        foreach ($seasons as $season) {
            switch ($season) {
                case 'warm_summer':
                    $field = $prefix ? 'c.outerWarmSummer' : 'c.warmSummer';
                    $conditions[] = $field . ' = true';
                    break;
                case 'cool_summer_warm_spring_autumn':
                    $field = $prefix ? 'c.outerCoolSummerWarmSpringAutumn' : 'c.coolSummerWarmSpringAutumn';
                    $conditions[] = $field . ' = true';
                    break;
                case 'cool_spring_autumn_warm_winter':
                    $field = $prefix ? 'c.outerCoolSpringAutumnWarmWinter' : 'c.coolSpringAutumnWarmWinter';
                    $conditions[] = $field . ' = true';
                    break;
                case 'cold_winter':
                    $field = $prefix ? 'c.outerColdWinter' : 'c.coldWinter';
                    $conditions[] = $field . ' = true';
                    break;
            }
        }

        if (!empty($conditions)) {
            $qb->where(implode(' OR ', $conditions));
        }

        if ($clothingType) {
            $qb->andWhere('c.item = :clothingType')
               ->setParameter('clothingType', $clothingType);
        }

        return $qb->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getCatalogStats(): array
    {
        $totalItems = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $mainClothingItems = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.item = :item')
            ->setParameter('item', 'Основная одежда')
            ->getQuery()
            ->getSingleScalarResult();

        $outerClothingItems = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.item = :item')
            ->setParameter('item', 'Верхняя одежда')
            ->getQuery()
            ->getSingleScalarResult();

        $uniqueBaseTypes = $this->createQueryBuilder('c')
            ->select('COUNT(DISTINCT c.baseType)')
            ->where('c.baseType IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'total_items' => (int) $totalItems,
            'main_clothing_items' => (int) $mainClothingItems,
            'outer_clothing_items' => (int) $outerClothingItems,
            'unique_base_types' => (int) $uniqueBaseTypes
        ];
    }
}
