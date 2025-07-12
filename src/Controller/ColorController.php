<?php

namespace App\Controller;

use App\Entity\ColorRecord;
use App\Form\ColorRecordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ColorController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/colors', name: 'color_index')]
    public function index(): Response
    {
        $records = $this->entityManager
            ->getRepository(ColorRecord::class)
            ->findBy([], ['id' => 'DESC']);

        return $this->render('color/index.html.twig', [
            'records' => $records,
        ]);
    }

    #[Route('/colors/create', name: 'color_create')]
    public function create(Request $request): Response
    {
        $record = new ColorRecord();
        $form = $this->createForm(ColorRecordType::class, $record);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($record);
            $this->entityManager->flush();

            $this->addFlash('success', 'color_created_successfully');

            return $this->redirectToRoute('color_index');
        }

        return $this->render('color/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/colors/edit/{id}', name: 'color_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, ColorRecord $record): Response
    {
        $form = $this->createForm(ColorRecordType::class, $record);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'color_updated_successfully');

            return $this->redirectToRoute('color_index');
        }

        return $this->render('color/edit.html.twig', [
            'form' => $form->createView(),
            'record' => $record,
        ]);
    }

    #[Route('/colors/view/{id}', name: 'color_view', requirements: ['id' => '\d+'])]
    public function view(ColorRecord $record): Response
    {
        return $this->render('color/view.html.twig', [
            'record' => $record,
        ]);
    }

    #[Route('/colors/delete/{id}', name: 'color_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, ColorRecord $record): Response
    {
        if ($this->isCsrfTokenValid('delete'.$record->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($record);
            $this->entityManager->flush();

            $this->addFlash('success', 'color_deleted_successfully');
        }

        return $this->redirectToRoute('color_index');
    }
}
