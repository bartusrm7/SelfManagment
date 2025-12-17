<?php

namespace App\Controller;

use App\Entity\DailyTasks;
use App\Form\DailyTasksFilterType;
use App\Form\DailyTasksType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DailyTasksController extends AbstractController
{
    #[Route('/daily-tasks', name: 'app_daily_tasks')]
    public function displayTasks(Request $request, EntityManagerInterface $entityManager): Response
    {
        $taskDate = new DailyTasks();
        $taskDate->setTaskDate(new \DateTime());

        $filter = $this->createForm(DailyTasksFilterType::class, $taskDate);
        $filter->handleRequest($request);

        $tasks = $entityManager->getRepository(DailyTasks::class)->findBy(['taskDate' => $taskDate->getTaskDate()]);

        if ($filter->isSubmitted() && $filter->isValid()) {
            $selectedDate = $filter->getData()->getTaskDate();
            $tasks = $entityManager->getRepository(DailyTasks::class)->findBy(['taskDate' => $selectedDate]);
        }

        return $this->render('daily_tasks/daily_tasks.html.twig', [
            'filter' => $filter->createView(),
            'tasks' => $tasks
        ]);
    }

    #[Route('/daily-tasks/create-task', 'app_create_daily_tasks')]
    public function createTask(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new DailyTasks();
        $form = $this->createForm(DailyTasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setTaskDate(new \DateTime());
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('app_daily_tasks');
        }

        return $this->render('daily_tasks/create-task.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/daily-tasks/done-task/{id}', 'app_done_daily_task', methods: ['POST'])]
    public function doneTask(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $task = $entityManager->getRepository(DailyTasks::class)->find($id);
        if ($task) {
            $task->setTaskDone(!$task->getTaskDone());
            $entityManager->flush();
        }
        return new JsonResponse([
            'success' => true,
            'done' => $task->getTaskDone(),
        ]);
    }

    #[Route('/daily-tasks/edit-task/{id}', 'app_edit_daily_task', methods: ['GET', 'POST'])]
    public function editTask(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $task = $entityManager->getRepository(DailyTasks::class)->find($id);
        $form = $this->createForm(DailyTasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_daily_tasks');
        }

        return $this->render('daily_tasks/edit-task.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/daily-tasks/remove-task/{id}', 'app_remove_daily_task', methods: ['DELETE'])]
    public function removeTask(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $task = $entityManager->getRepository(DailyTasks::class)->find($id);
        if ($task) {
            $entityManager->remove($task);
            $entityManager->flush();
        }
    }
}
