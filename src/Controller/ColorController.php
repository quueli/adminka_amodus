<?php

namespace App\Controller;

use App\Entity\ColorRecord;
use App\Form\ColorRecordType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ColorController extends BaseController
{
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

        $response = $this->handleForm(
            $form,
            $record,
            $request,
            'color_created_successfully',
            'color_index',
            true
        );

        if ($response) {
            return $response;
        }

        return $this->createCrudResponse('color/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/colors/edit/{id}', name: 'color_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, ColorRecord $record): Response
    {
        $form = $this->createForm(ColorRecordType::class, $record);

        $response = $this->handleForm(
            $form,
            $record,
            $request,
            'color_updated_successfully',
            'color_index'
        );

        if ($response) {
            return $response;
        }

        return $this->createCrudResponse('color/edit.html.twig', [
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
        return $this->deleteEntity(
            $record,
            $request,
            'delete'.$record->getId(),
            'color_deleted_successfully',
            'color_index'
        );
    }
}
