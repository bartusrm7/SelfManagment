<?php

namespace App\Controller;

use App\Entity\DailyTasks;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();

        $taskDate = new \DateTime('today');
        $tasks = $entityManager->getRepository(DailyTasks::class)->findBy(['taskDate' => $taskDate, 'user' => $user]);

        $percentageDone = null;
        $tasksDone = array_filter($tasks, fn($task) => $task->getTaskDone());
        if ($tasks) {
            $percentageDone = (count($tasksDone) / count($tasks) * 100);
        }

        return $this->render('dashboard/dashboard.html.twig', [
            'tasks' => $tasks,
            'percentage' => $percentageDone
        ]);
    }
}
