<?php

namespace App\Tests\Controller;

use App\Entity\Nomenclature;
use App\Entity\NomenclatureCharacteristicValue;
use App\Entity\CharacteristicAvailableValue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NomenclatureEditTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testEditNomenclatureWithoutDuplicateConstraintViolation(): void
    {
        $client = static::createClient();

        // Найдем существующую номенклатуру для тестирования
        $nomenclature = $this->entityManager
            ->getRepository(Nomenclature::class)
            ->findOneBy([]);

        if (!$nomenclature) {
            $this->markTestSkipped('No nomenclature found for testing');
        }

        // Получим доступные значения характеристик
        $availableValues = $this->entityManager
            ->getRepository(CharacteristicAvailableValue::class)
            ->findAll();

        if (count($availableValues) < 2) {
            $this->markTestSkipped('Not enough characteristic values for testing');
        }

        // Подготовим данные для формы
        $formData = [
            'nomenclature_multiple[nomenclatureName]' => 'Тестовая номенклатура ' . time(),
        ];

        // Добавим несколько характеристик
        $selectedValues = array_slice($availableValues, 0, 2);
        foreach ($selectedValues as $value) {
            $fieldName = 'nomenclature_multiple[characteristic_' . $value->getCharacteristic()->getId() . '][]';
            $formData[$fieldName] = $value->getId();
        }

        // Отправим форму редактирования
        $crawler = $client->request('GET', '/nomenclature/edit/' . $nomenclature->getId());
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Сохранить')->form();
        
        // Заполним форму
        foreach ($formData as $fieldName => $value) {
            if (is_array($value)) {
                $form[$fieldName] = $value;
            } else {
                $form[$fieldName] = $value;
            }
        }

        // Отправим форму
        $client->submit($form);

        // Проверим, что нет ошибки дублирования
        $this->assertResponseRedirects('/nomenclature');
        
        // Проверим, что данные сохранились корректно
        $this->entityManager->refresh($nomenclature);
        $connections = $this->entityManager
            ->getRepository(NomenclatureCharacteristicValue::class)
            ->findBy(['nomenclature' => $nomenclature]);

        $this->assertGreaterThan(0, count($connections), 'Connections should be created');

        // Проверим, что нет дублирующихся связей
        $uniqueConnections = [];
        foreach ($connections as $connection) {
            $key = $connection->getNomenclature()->getId() . '-' . $connection->getCharacteristicAvailableValue()->getId();
            $this->assertArrayNotHasKey($key, $uniqueConnections, 'No duplicate connections should exist');
            $uniqueConnections[$key] = true;
        }
    }

    public function testRepositoryUpdateNomenclatureConnections(): void
    {
        // Найдем существующую номенклатуру
        $nomenclature = $this->entityManager
            ->getRepository(Nomenclature::class)
            ->findOneBy([]);

        if (!$nomenclature) {
            $this->markTestSkipped('No nomenclature found for testing');
        }

        // Получим доступные значения
        $availableValues = $this->entityManager
            ->getRepository(CharacteristicAvailableValue::class)
            ->findAll();

        if (count($availableValues) < 3) {
            $this->markTestSkipped('Not enough characteristic values for testing');
        }

        $repository = $this->entityManager->getRepository(NomenclatureCharacteristicValue::class);

        // Тестируем метод updateNomenclatureConnections
        $selectedValueIds = [
            $availableValues[0]->getId(),
            $availableValues[1]->getId(),
            $availableValues[2]->getId()
        ];

        // Вызываем метод обновления
        $repository->updateNomenclatureConnections($nomenclature, $selectedValueIds);

        // Проверяем результат
        $connections = $repository->findBy(['nomenclature' => $nomenclature]);
        $this->assertCount(3, $connections, 'Should have exactly 3 connections');

        // Проверяем, что нет дублирующихся связей
        $connectionKeys = [];
        foreach ($connections as $connection) {
            $key = $connection->getNomenclature()->getId() . '-' . $connection->getCharacteristicAvailableValue()->getId();
            $this->assertNotContains($key, $connectionKeys, 'No duplicate connections should exist');
            $connectionKeys[] = $key;
        }

        // Тестируем повторный вызов с теми же данными (не должно создавать дубликаты)
        $repository->updateNomenclatureConnections($nomenclature, $selectedValueIds);
        
        $connectionsAfterSecondCall = $repository->findBy(['nomenclature' => $nomenclature]);
        $this->assertCount(3, $connectionsAfterSecondCall, 'Should still have exactly 3 connections after second call');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
