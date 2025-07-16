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

    /**
     * Найти все корневые элементы (без родителя)
     */
    public function findRootItems(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.parent IS NULL')
            ->orderBy('c.sortOrder', 'ASC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Найти дочерние элементы для указанного родителя
     */
    public function findChildrenByParent(CatalogItem $parent): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.parent = :parent')
            ->setParameter('parent', $parent)
            ->orderBy('c.sortOrder', 'ASC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Получить полное дерево каталога
     */
    public function findTree(): array
    {
        $allItems = $this->createQueryBuilder('c')
            ->orderBy('c.sortOrder', 'ASC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->buildTree($allItems);
    }

    /**
     * Построить дерево из плоского массива элементов
     */
    private function buildTree(array $items, ?CatalogItem $parent = null): array
    {
        $tree = [];
        
        foreach ($items as $item) {
            if ($item->getParent() === $parent) {
                $children = $this->buildTree($items, $item);
                if (!empty($children)) {
                    $item->children = $children;
                }
                $tree[] = $item;
            }
        }
        
        return $tree;
    }

    /**
     * Найти элементы по уровню в дереве
     */
    public function findByLevel(int $level): array
    {
        $qb = $this->createQueryBuilder('c');
        
        // Строим запрос для поиска по уровню
        for ($i = 0; $i < $level; $i++) {
            if ($i === 0) {
                $qb->join('c.parent', 'p' . $i);
            } else {
                $qb->join('p' . ($i - 1) . '.parent', 'p' . $i);
            }
        }
        
        if ($level === 0) {
            $qb->where('c.parent IS NULL');
        } else {
            $qb->where('p' . ($level - 1) . '.parent IS NULL');
        }
        
        return $qb->orderBy('c.sortOrder', 'ASC')
            ->addOrderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Поиск элементов по названию
     */
    public function findByNameLike(string $searchTerm): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.name LIKE :searchTerm')
            ->orWhere('c.synonym LIKE :searchTerm')
            ->orWhere('c.itemName LIKE :searchTerm')
            ->orWhere('c.mainItem LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Получить путь от корня до указанного элемента
     */
    public function getPathToRoot(CatalogItem $item): array
    {
        $path = [];
        $current = $item;
        
        while ($current !== null) {
            array_unshift($path, $current);
            $current = $current->getParent();
        }
        
        return $path;
    }

    /**
     * Проверить, является ли один элемент потомком другого
     */
    public function isDescendantOf(CatalogItem $child, CatalogItem $ancestor): bool
    {
        $current = $child->getParent();
        
        while ($current !== null) {
            if ($current === $ancestor) {
                return true;
            }
            $current = $current->getParent();
        }
        
        return false;
    }

    /**
     * Получить все потомки элемента
     */
    public function findDescendants(CatalogItem $parent): array
    {
        $descendants = [];
        $this->collectDescendants($parent, $descendants);
        return $descendants;
    }

    private function collectDescendants(CatalogItem $parent, array &$descendants): void
    {
        foreach ($parent->getChildren() as $child) {
            $descendants[] = $child;
            $this->collectDescendants($child, $descendants);
        }
    }

    /**
     * Получить максимальный порядок сортировки для дочерних элементов
     */
    public function getMaxSortOrderForParent(?CatalogItem $parent): int
    {
        $qb = $this->createQueryBuilder('c')
            ->select('MAX(c.sortOrder)')
            ->where('c.parent = :parent')
            ->setParameter('parent', $parent);

        if ($parent === null) {
            $qb->where('c.parent IS NULL');
        }

        $result = $qb->getQuery()->getSingleScalarResult();
        
        return $result ? (int) $result : 0;
    }

    /**
     * Найти элементы по сезонным критериям
     */
    public function findBySeasons(array $seasons): array
    {
        $qb = $this->createQueryBuilder('c');
        $conditions = [];
        
        foreach ($seasons as $seasonNumber) {
            if ($seasonNumber >= 1 && $seasonNumber <= 8) {
                $conditions[] = 'c.season' . $seasonNumber . ' = true';
            }
        }
        
        if (!empty($conditions)) {
            $qb->where(implode(' OR ', $conditions));
        }
        
        return $qb->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Статистика по каталогу
     */
    public function getCatalogStats(): array
    {
        $totalItems = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $rootItems = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.parent IS NULL')
            ->getQuery()
            ->getSingleScalarResult();

        $leafItems = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->leftJoin('c.children', 'children')
            ->having('COUNT(children.id) = 0')
            ->groupBy('c.id')
            ->getQuery()
            ->getResult();

        return [
            'total_items' => (int) $totalItems,
            'root_items' => (int) $rootItems,
            'leaf_items' => count($leafItems),
            'branch_items' => (int) $totalItems - count($leafItems)
        ];
    }
}
