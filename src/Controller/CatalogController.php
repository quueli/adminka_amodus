<?php

namespace App\Controller;

use App\Entity\CatalogItem;
use App\Form\CatalogItemType;
use App\Repository\CatalogItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/catalog')]
class CatalogController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        private CatalogItemRepository $catalogRepository
    ) {
        parent::__construct($entityManager);
    }

    #[Route('', name: 'catalog_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $search = $request->query->get('search');
        $parentId = $request->query->get('parent');
        
        if ($search) {
            $items = $this->catalogRepository->findByNameLike($search);
            $tree = [];
        } elseif ($parentId) {
            $parent = $this->catalogRepository->find($parentId);
            $items = $parent ? $this->catalogRepository->findChildrenByParent($parent) : [];
            $tree = [];
        } else {
            $items = $this->catalogRepository->findRootItems();
            $tree = $this->catalogRepository->findTree();
        }

        $stats = $this->catalogRepository->getCatalogStats();

        return $this->render('catalog/index.html.twig', [
            'items' => $items,
            'tree' => $tree,
            'stats' => $stats,
            'search' => $search,
            'currentParent' => $parentId ? $this->catalogRepository->find($parentId) : null
        ]);
    }

    #[Route('/create', name: 'catalog_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $catalogItem = new CatalogItem();
        
        // Если указан родительский элемент в параметрах
        $parentId = $request->query->get('parent');
        if ($parentId) {
            $parent = $this->catalogRepository->find($parentId);
            if ($parent) {
                $catalogItem->setParent($parent);
                // Устанавливаем следующий порядок сортировки
                $maxOrder = $this->catalogRepository->getMaxSortOrderForParent($parent);
                $catalogItem->setSortOrder($maxOrder + 1);
            }
        }

        $form = $this->createForm(CatalogItemType::class, $catalogItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $catalogItem->setUpdatedAt(new \DateTime());
            
            $this->entityManager->persist($catalogItem);
            $this->entityManager->flush();

            $this->addFlash('success', 'catalog_item_created_successfully');
            
            return $this->redirectToRoute('catalog_index');
        }

        return $this->render('catalog/create.html.twig', [
            'form' => $form->createView(),
            'catalogItem' => $catalogItem
        ]);
    }

    #[Route('/show/{id}', name: 'catalog_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(CatalogItem $catalogItem): Response
    {
        $children = $this->catalogRepository->findChildrenByParent($catalogItem);
        $path = $this->catalogRepository->getPathToRoot($catalogItem);
        
        return $this->render('catalog/view.html.twig', [
            'catalogItem' => $catalogItem,
            'children' => $children,
            'path' => $path
        ]);
    }

    #[Route('/edit/{id}', name: 'catalog_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, CatalogItem $catalogItem): Response
    {
        $form = $this->createForm(CatalogItemType::class, $catalogItem);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Проверяем, что элемент не назначается сам себе в качестве родителя
            if ($catalogItem->getParent() === $catalogItem) {
                $this->addFlash('error', 'catalog_item_cannot_be_parent_of_itself');
                return $this->render('catalog/edit.html.twig', [
                    'form' => $form->createView(),
                    'catalogItem' => $catalogItem
                ]);
            }

            // Проверяем, что элемент не назначается потомку в качестве родителя
            if ($catalogItem->getParent() && 
                $this->catalogRepository->isDescendantOf($catalogItem->getParent(), $catalogItem)) {
                $this->addFlash('error', 'catalog_item_cannot_be_parent_of_descendant');
                return $this->render('catalog/edit.html.twig', [
                    'form' => $form->createView(),
                    'catalogItem' => $catalogItem
                ]);
            }

            $catalogItem->setUpdatedAt(new \DateTime());
            $this->entityManager->flush();

            $this->addFlash('success', 'catalog_item_updated_successfully');
            
            return $this->redirectToRoute('catalog_show', ['id' => $catalogItem->getId()]);
        }

        return $this->render('catalog/edit.html.twig', [
            'form' => $form->createView(),
            'catalogItem' => $catalogItem
        ]);
    }

    #[Route('/delete/{id}', name: 'catalog_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, CatalogItem $catalogItem): Response
    {
        return $this->deleteEntity(
            $catalogItem,
            $request,
            'delete' . $catalogItem->getId(),
            'catalog_item_deleted_successfully',
            'catalog_index'
        );
    }

    #[Route('/tree', name: 'catalog_tree', methods: ['GET'])]
    public function tree(): Response
    {
        $tree = $this->catalogRepository->findTree();
        
        return $this->render('catalog/tree.html.twig', [
            'tree' => $tree
        ]);
    }

    #[Route('/move/{id}', name: 'catalog_move', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function move(Request $request, CatalogItem $catalogItem): Response
    {
        $newParentId = $request->request->get('new_parent_id');
        $newParent = $newParentId ? $this->catalogRepository->find($newParentId) : null;

        // Проверяем валидность перемещения
        if ($newParent && $this->catalogRepository->isDescendantOf($newParent, $catalogItem)) {
            $this->addFlash('error', 'catalog_item_cannot_be_moved_to_descendant');
            return $this->redirectToRoute('catalog_show', ['id' => $catalogItem->getId()]);
        }

        $catalogItem->setParent($newParent);
        $catalogItem->setUpdatedAt(new \DateTime());
        
        $this->entityManager->flush();

        $this->addFlash('success', 'catalog_item_moved_successfully');
        
        return $this->redirectToRoute('catalog_show', ['id' => $catalogItem->getId()]);
    }

    #[Route('/api/children/{id}', name: 'catalog_api_children', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function apiChildren(CatalogItem $catalogItem): Response
    {
        $children = $this->catalogRepository->findChildrenByParent($catalogItem);
        
        $data = array_map(function (CatalogItem $item) {
            return [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'hasChildren' => $item->hasChildren(),
                'level' => $item->getLevel()
            ];
        }, $children);

        return $this->json($data);
    }
}
