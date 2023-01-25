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
            $image = $form->get('photo')->getData();
            if ($image) {
                $imageName = $task->getName() . uniqid() . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $imageName
                );
                $task->setPhoto($imageName);
            }
            $task->setUser($this->getUser());
            $this->entityManager->persist($task);
            $this->entityManager->flush();
            $this->addFlash('success', 'La tâche a bien été créée');
            return $this->redirectToRoute('task_index');
        }
        return $this->render('task/create.html.twig', [
            'buttontext' => 'Créer',
            'form' => $form,
        ]);
    }

    #[Route('/modifier/{id}', name: 'edit')]
    public function edit(Task $task, Request $request): Response
    {
        $form = $this->createForm(TaskFormTrypeType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('photo')->getData();
            if ($image) {
                $imageName = $task->getName() . uniqid() . '.' . $image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $imageName
                );
                $task->setPhoto($imageName);
            }
            $this->entityManager->flush();
            $this->addFlash('success', 'La tâche a bien été modifiée');
            return $this->redirectToRoute('task_index');
        }
        return $this->render('task/edit.html.twig', [
            'buttontext' => 'Modifier',
            'form' => $form,
            'task' => $task,
        ]);
    }

    #[Route('/supprimer/{id}', name: 'delete')]
    public function delete(Task $task): Response
    {
        unlink($this->getParameter('images_directory') . '/' . $task->getPhoto());
        $this->entityManager->remove($task);
        $this->entityManager->flush();
        $this->addFlash('success', 'La tâche a bien été supprimée');
        return $this->redirectToRoute('task_index');
    }
}