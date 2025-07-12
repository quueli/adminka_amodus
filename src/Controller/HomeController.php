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
        return $this->redirectToRoute('color_index');
    }

    #[Route('/test', name: 'test')]
    public function test(): Response
    {
        return new Response('<h1>hello world</h1><p>Symfony запущен корректно.</p><a href="/colors">Перейти к управлению цветами</a>');
    }
}
