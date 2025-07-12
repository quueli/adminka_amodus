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
        // Получаем все характеристики для создания динамических столбцов
        $allCharacteristics = $this->entityManager
            ->getRepository(Characteristic::class)
            ->findBy([], ['name' => 'ASC']);

        // Получаем все доступные значения для форм создания
        $availableValues = $this->entityManager
            ->getRepository(CharacteristicAvailableValue::class)
            ->findBy([], ['id' => 'DESC']);

        // Получаем номенклатуры с предзагруженными связями для оптимизации
        $nomenclatures = $this->entityManager
            ->createQueryBuilder()
            ->select('n', 'ncv', 'cav', 'c')
            ->from(Nomenclature::class, 'n')
            ->leftJoin('n.nomenclatureCharacteristicValues', 'ncv')
            ->leftJoin('ncv.characteristicAvailableValue', 'cav')
            ->leftJoin('cav.characteristic', 'c')
            ->orderBy('n.id', 'DESC')
            ->getQuery()
            ->getResult();

        // Создаем структуру данных для удобного отображения в шаблоне
        $nomenclatureData = [];
        foreach ($nomenclatures as $nomenclature) {
            $nomenclatureId = $nomenclature->getId();

            if (!isset($nomenclatureData[$nomenclatureId])) {
                $nomenclatureData[$nomenclatureId] = [
                    'nomenclature' => $nomenclature,
                    'characteristics' => []
                ];
            }

            // Группируем значения по характеристикам
            foreach ($nomenclature->getNomenclatureCharacteristicValues() as $ncv) {
                $characteristic = $ncv->getCharacteristicAvailableValue()->getCharacteristic();
                $characteristicId = $characteristic->getId();

                if (!isset($nomenclatureData[$nomenclatureId]['characteristics'][$characteristicId])) {
                    $nomenclatureData[$nomenclatureId]['characteristics'][$characteristicId] = [];
                }

                $nomenclatureData[$nomenclatureId]['characteristics'][$characteristicId][] =
                    $ncv->getCharacteristicAvailableValue()->getValue();
            }
        }

        return $this->render('nomenclature/index.html.twig', [
            'allCharacteristics' => $allCharacteristics,
            'availableValues' => $availableValues,
            'nomenclatureData' => $nomenclatureData,
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
            $this->entityManager->persist($nomenclature);

            $selectedValues = $form->get('characteristicAvailableValues')->getData();

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

        $currentValues = $nomenclature->getCharacteristicAvailableValues();
        $form->get('characteristicAvailableValues')->setData($currentValues);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($nomenclature->getNomenclatureCharacteristicValues() as $ncv) {
                $this->entityManager->remove($ncv);
            }

            $selectedValues = $form->get('characteristicAvailableValues')->getData();

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
