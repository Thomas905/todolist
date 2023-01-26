<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('auth_login');
        }
        return $this->redirectToRoute('task_index');
    }
}