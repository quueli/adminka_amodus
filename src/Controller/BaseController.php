<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {}

    /**
     * Обработка стандартной формы создания/редактирования
     */
    protected function handleForm(
        $form,
        $entity,
        Request $request,
        string $successMessage,
        string $redirectRoute,
        bool $isNew = false
    ): ?Response {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($isNew) {
                $this->entityManager->persist($entity);
            }
            
            $this->entityManager->flush();
            $this->addFlash('success', $successMessage);

            return $this->redirectToRoute($redirectRoute);
        }

        return null;
    }

    /**
     * Безопасное удаление сущности
     */
    protected function deleteEntity(
        $entity,
        Request $request,
        string $tokenId,
        string $successMessage,
        string $redirectRoute
    ): Response {
        if ($this->isCsrfTokenValid($tokenId, $request->request->get('_token'))) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();

            $this->addFlash('success', $successMessage);
        } else {
            $this->addFlash('error', 'invalid_csrf_token');
        }

        return $this->redirectToRoute($redirectRoute);
    }

    /**
     * Создание стандартного ответа для CRUD операций
     */
    protected function createCrudResponse(
        string $template,
        array $data = []
    ): Response {
        return $this->render($template, $data);
    }

    /**
     * Получение сущности или возврат 404
     */
    protected function getEntityOr404(string $entityClass, int $id)
    {
        $entity = $this->entityManager->getRepository($entityClass)->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('Entity %s with id %d not found', $entityClass, $id));
        }

        return $entity;
    }

    /**
     * Добавление flash сообщения с переводом
     */
    protected function addTranslatedFlash(string $type, string $messageKey, array $parameters = []): void
    {
        $this->addFlash($type, $this->trans($messageKey, $parameters));
    }

    /**
     * Перевод сообщения
     */
    protected function trans(string $id, array $parameters = [], string $domain = 'messages'): string
    {
        return $this->container->get('translator')->trans($id, $parameters, $domain);
    }
}
