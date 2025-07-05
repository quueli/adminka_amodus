<?php

namespace App\Controller;

use App\Entity\TableRecord;
use App\Form\TableRecordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/', name: 'admin_index')]
    public function index(): Response
    {
        $records = $this->entityManager
            ->getRepository(TableRecord::class)
            ->findBy([], ['id' => 'DESC']);

        return $this->render('admin/index.html.twig', [
            'records' => $records,
        ]);
    }

    #[Route('/create', name: 'admin_create')]
    public function create(Request $request): Response
    {
        $record = new TableRecord();
        $form = $this->createForm(TableRecordType::class, $record);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($record);
            $this->entityManager->flush();

            $this->addFlash('success', 'Record created successfully!');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'admin_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, TableRecord $record): Response
    {
        $form = $this->createForm(TableRecordType::class, $record);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Record updated successfully!');

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
            'record' => $record,
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, TableRecord $record): Response
    {
        if ($this->isCsrfTokenValid('delete'.$record->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($record);
            $this->entityManager->flush();

            $this->addFlash('success', 'Record deleted successfully!');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('admin_index');
    }

    #[Route('/view/{id}', name: 'admin_view', requirements: ['id' => '\d+'])]
    public function view(TableRecord $record): Response
    {
        return $this->render('admin/view.html.twig', [
            'record' => $record,
        ]);
    }
}
