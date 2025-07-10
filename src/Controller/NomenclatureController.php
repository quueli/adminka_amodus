<?php

namespace App\Controller;

use App\Entity\Characteristic;
use App\Entity\CharacteristicAvailableValue;
use App\Entity\Nomenclature;
use App\Entity\NomenclatureCharacteristicValue;
use App\Form\CharacteristicType;
use App\Form\CharacteristicAvailableValueType;
use App\Form\NomenclatureType;
use App\Form\NomenclatureMultipleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class NomenclatureController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/nomenclature', name: 'nomenclature_index')]
    public function index(): Response
    {
        $characteristics = $this->entityManager
            ->getRepository(Characteristic::class)
            ->findBy([], ['id' => 'DESC']);

        $availableValues = $this->entityManager
            ->getRepository(CharacteristicAvailableValue::class)
            ->findBy([], ['id' => 'DESC']);

        $nomenclatures = $this->entityManager
            ->getRepository(Nomenclature::class)
            ->findBy([], ['id' => 'DESC']);

        return $this->render('nomenclature/index.html.twig', [
            'characteristics' => $characteristics,
            'availableValues' => $availableValues,
            'nomenclatures' => $nomenclatures,
        ]);
    }

    #[Route('/nomenclature/characteristic/create', name: 'nomenclature_characteristic_create')]
    public function createCharacteristic(Request $request): Response
    {
        $characteristic = new Characteristic();
        $form = $this->createForm(CharacteristicType::class, $characteristic);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($characteristic);
            $this->entityManager->flush();

            $this->addFlash('success', 'characteristic_created_successfully');

            return $this->redirectToRoute('nomenclature_index');
        }

        return $this->render('nomenclature/characteristic/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/nomenclature/characteristic/edit/{id}', name: 'nomenclature_characteristic_edit', requirements: ['id' => '\d+'])]
    public function editCharacteristic(Request $request, Characteristic $characteristic): Response
    {
        $form = $this->createForm(CharacteristicType::class, $characteristic);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'characteristic_updated_successfully');

            return $this->redirectToRoute('nomenclature_index');
        }

        return $this->render('nomenclature/characteristic/edit.html.twig', [
            'form' => $form->createView(),
            'characteristic' => $characteristic,
        ]);
    }

    #[Route('/nomenclature/characteristic/delete/{id}', name: 'nomenclature_characteristic_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deleteCharacteristic(Request $request, Characteristic $characteristic): Response
    {
        $this->entityManager->remove($characteristic);
        $this->entityManager->flush();

        $this->addFlash('success', 'characteristic_deleted_successfully');

        return $this->redirectToRoute('nomenclature_index');
    }

    #[Route('/nomenclature/available-value/create', name: 'nomenclature_available_value_create')]
    public function createAvailableValue(Request $request): Response
    {
        $availableValue = new CharacteristicAvailableValue();
        $form = $this->createForm(CharacteristicAvailableValueType::class, $availableValue);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($availableValue);
            $this->entityManager->flush();

            $this->addFlash('success', 'available_value_created_successfully');

            return $this->redirectToRoute('nomenclature_index');
        }

        return $this->render('nomenclature/available_value/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/nomenclature/available-value/edit/{id}', name: 'nomenclature_available_value_edit', requirements: ['id' => '\d+'])]
    public function editAvailableValue(Request $request, CharacteristicAvailableValue $availableValue): Response
    {
        $form = $this->createForm(CharacteristicAvailableValueType::class, $availableValue);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'available_value_updated_successfully');

            return $this->redirectToRoute('nomenclature_index');
        }

        return $this->render('nomenclature/available_value/edit.html.twig', [
            'form' => $form->createView(),
            'availableValue' => $availableValue,
        ]);
    }

    #[Route('/nomenclature/available-value/delete/{id}', name: 'nomenclature_available_value_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deleteAvailableValue(Request $request, CharacteristicAvailableValue $availableValue): Response
    {
        $this->entityManager->remove($availableValue);
        $this->entityManager->flush();

        $this->addFlash('success', 'available_value_deleted_successfully');

        return $this->redirectToRoute('nomenclature_index');
    }

    #[Route('/nomenclature/create', name: 'nomenclature_create')]
    public function createNomenclature(Request $request): Response
    {
        $nomenclature = new Nomenclature();
        $form = $this->createForm(NomenclatureMultipleType::class, $nomenclature);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Сохраняем номенклатуру
            $this->entityManager->persist($nomenclature);

            // Получаем выбранные доступные значения из формы
            $selectedValues = $form->get('characteristicAvailableValues')->getData();

            // Создаем связи между номенклатурой и доступными значениями
            foreach ($selectedValues as $availableValue) {
                $nomenclatureCharacteristicValue = new NomenclatureCharacteristicValue();
                $nomenclatureCharacteristicValue->setNomenclature($nomenclature);
                $nomenclatureCharacteristicValue->setCharacteristicAvailableValue($availableValue);

                $this->entityManager->persist($nomenclatureCharacteristicValue);
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'nomenclature_created_successfully');

            return $this->redirectToRoute('nomenclature_index');
        }

        return $this->render('nomenclature/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/nomenclature/edit/{id}', name: 'nomenclature_edit', requirements: ['id' => '\d+'])]
    public function editNomenclature(Request $request, Nomenclature $nomenclature): Response
    {
        $form = $this->createForm(NomenclatureMultipleType::class, $nomenclature);

        // Предзаполняем форму текущими доступными значениями
        $currentValues = $nomenclature->getCharacteristicAvailableValues();
        $form->get('characteristicAvailableValues')->setData($currentValues);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Удаляем все существующие связи
            foreach ($nomenclature->getNomenclatureCharacteristicValues() as $ncv) {
                $this->entityManager->remove($ncv);
            }

            // Получаем новые выбранные доступные значения из формы
            $selectedValues = $form->get('characteristicAvailableValues')->getData();

            // Создаем новые связи
            foreach ($selectedValues as $availableValue) {
                $nomenclatureCharacteristicValue = new NomenclatureCharacteristicValue();
                $nomenclatureCharacteristicValue->setNomenclature($nomenclature);
                $nomenclatureCharacteristicValue->setCharacteristicAvailableValue($availableValue);

                $this->entityManager->persist($nomenclatureCharacteristicValue);
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'nomenclature_updated_successfully');

            return $this->redirectToRoute('nomenclature_index');
        }

        return $this->render('nomenclature/edit.html.twig', [
            'form' => $form->createView(),
            'nomenclature' => $nomenclature,
        ]);
    }

    #[Route('/nomenclature/delete/{id}', name: 'nomenclature_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deleteNomenclature(Request $request, Nomenclature $nomenclature): Response
    {
        $this->entityManager->remove($nomenclature);
        $this->entityManager->flush();

        $this->addFlash('success', 'nomenclature_deleted_successfully');

        return $this->redirectToRoute('nomenclature_index');
    }

    #[Route('/api/available-values/{characteristicId}', name: 'api_available_values', requirements: ['characteristicId' => '\d+'])]
    public function getAvailableValues(int $characteristicId): JsonResponse
    {
        $availableValues = $this->entityManager
            ->getRepository(CharacteristicAvailableValue::class)
            ->findBy(['characteristic' => $characteristicId]);

        $data = [];
        foreach ($availableValues as $value) {
            $data[] = [
                'id' => $value->getId(),
                'value' => $value->getValue(),
            ];
        }

        return new JsonResponse($data);
    }
}
