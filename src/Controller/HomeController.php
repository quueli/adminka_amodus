<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->redirectToRoute('admin_index');
    }

    #[Route('/test', name: 'test')]
    public function test(): Response
    {
        return new Response('<h1>Тест работает!</h1><p>Symfony запущен корректно.</p><a href="/admin">Перейти к админ-панели</a>');
    }
}
