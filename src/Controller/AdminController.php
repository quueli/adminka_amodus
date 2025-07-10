<?php

namespace App\Controller;

use App\Entity\ColorRecord;
use App\Form\ColorRecordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/admin', name: 'admin_index')]
    public function index(): Response
    {
        $records = $this->entityManager
            ->getRepository(ColorRecord::class)
            ->findBy([], ['id' => 'DESC']);

        return $this->render('admin/index.html.twig', [
            'records' => $records,
        ]);
    }

    #[Route('/admin/create', name: 'admin_create')]
    public function create(Request $request): Response
    {
        $record = new ColorRecord();
        $form = $this->createForm(ColorRecordType::class, $record);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($record);
            $this->entityManager->flush();

            $this->addFlash('success', 'record_created_successfully');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/edit/{id}', name: 'admin_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, ColorRecord $record): Response
    {
        $form = $this->createForm(ColorRecordType::class, $record);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'record_updated_successfully');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
            'record' => $record,
        ]);
    }

    #[Route('/admin/delete/{id}', name: 'admin_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, ColorRecord $record): Response
    {
        // не получилось использовать CSRF метод
        $this->entityManager->remove($record);
        $this->entityManager->flush();

        $this->addFlash('success', 'record_deleted_successfully');

        return $this->redirectToRoute('admin_index');
    }

    #[Route('/admin/view/{id}', name: 'admin_view', requirements: ['id' => '\d+'])]
    public function view(ColorRecord $record): Response
    {
        return $this->render('admin/view.html.twig', [
            'record' => $record,
        ]);
    }
}
