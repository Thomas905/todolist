<?php

namespace App\Controller\Task;

use App\Entity\Task;
use App\Form\TaskFormTrypeType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/task', name: 'task_')]
class TaskController extends AbstractController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'index')]
    public function index()
    {
        return $this->render('task/index.html.twig', [
            'tasks' => $this->getUser()->getTasks(),
        ]);
    }

    #[Route('/nouveau', name: 'new')]
    public function create(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskFormTrypeType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());
            $this->entityManager->persist($task);
            $this->entityManager->flush();
            return $this->redirectToRoute('task_index');
        }
        return $this->render('task/task.html.twig', [
            'text' => 'Créer une tâche',
            'form' => $form,
        ]);
    }

    #[Route('/modifier/{id}', name: 'edit')]
    public function edit(Task $task, Request $request): Response
    {
        $form = $this->createForm(TaskFormTrypeType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('task_index');
        }
        return $this->render('task/task.html.twig', [
            'text' => 'Modifier une tâche',
            'form' => $form,
        ]);
    }

    #[Route('/supprimer/{id}', name: 'delete')]
    public function delete(Task $task): Response
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();
        return $this->redirectToRoute('task_index');
    }
}