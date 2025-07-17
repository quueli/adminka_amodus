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
        $clothingType = $request->query->get('clothing_type');

        if ($search) {
            $items = $this->catalogRepository->findByNameLike($search);
        } elseif ($clothingType) {
            $items = $this->catalogRepository->findByClothingType($clothingType);
        } else {
            $items = $this->catalogRepository->findAllSorted();
        }

        $stats = $this->catalogRepository->getCatalogStats();
        $tree = $this->catalogRepository->buildHierarchicalTree();

        return $this->render('catalog/index.html.twig', [
            'items' => $items,
            'tree' => $tree,
            'stats' => $stats,
            'search' => $search,
            'clothingType' => $clothingType
        ]);
    }

    #[Route('/create', name: 'catalog_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $catalogItem = new CatalogItem();

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
        return $this->render('catalog/view.html.twig', [
            'catalogItem' => $catalogItem
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
        if ($this->isCsrfTokenValid('delete' . $catalogItem->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($catalogItem);
            $this->entityManager->flush();

            $this->addFlash('success', 'catalog_item_deleted_successfully');
        } else {
            $this->addFlash('error', 'invalid_csrf_token');
        }

        return $this->redirectToRoute('catalog_index');
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

    #[Route('/tree', name: 'catalog_tree', methods: ['GET'])]
    public function tree(): Response
    {
        $tree = $this->catalogRepository->buildHierarchicalTree();
        $stats = $this->catalogRepository->getCatalogStats();

        return $this->render('catalog/tree.html.twig', [
            'tree' => $tree,
            'stats' => $stats
        ]);
    }

    #[Route('/api/seasons', name: 'catalog_api_seasons', methods: ['GET'])]
    public function apiSeasons(Request $request): Response
    {
        $seasons = $request->query->all('seasons');
        $clothingType = $request->query->get('clothing_type');

        $items = $this->catalogRepository->findBySeasons($seasons, $clothingType);

        $data = array_map(function (CatalogItem $item) {
            return [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'hierarchyPath' => $item->getHierarchyPath(),
                'applicableSeasons' => $item->getApplicableSeasons(),
                'clothingType' => $item->getItem()
            ];
        }, $items);

        return $this->json($data);
    }
}
