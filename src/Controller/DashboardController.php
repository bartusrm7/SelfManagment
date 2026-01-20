<?php

namespace App\Controller;

use App\Entity\DailyTasks;
use App\Entity\Meetings;
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

        $today = new \DateTime('today');
        $tasks = $entityManager->getRepository(DailyTasks::class)->findBy(['taskDate' => $today, 'user' => $user]);

        $start = (clone $today)->setTime(0, 0, 0);
        $end = (clone $today)->setTime(23, 59, 59);

        $qb = $entityManager->createQueryBuilder();
        $qb->select('m')
            ->from(Meetings::class, 'm')
            ->where('m.user = :user')
            ->andWhere('m.startDate BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        $meetings = $qb->getQuery()->getResult();

        $percentageDone = null;
        $tasksDone = array_filter($tasks, fn($task) => $task->getTaskDone());
        if ($tasks) {
            $percentageDone = (count($tasksDone) / count($tasks) * 100);
        }

        return $this->render('dashboard/dashboard.html.twig', [
            'tasks' => $tasks,
            'meetings' => $meetings,
            'percentage' => $percentageDone
        ]);
    }
}
