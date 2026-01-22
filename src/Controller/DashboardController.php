<?php

namespace App\Controller;

use App\Entity\DailyTasks;
use App\Entity\Expenses;
use App\Entity\Meetings;
use DateTime;
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

        $start7 = (new \DateTime())->modify('-7 days')->setTime(0, 0);
        $end7 = new \DateTime();

        $qb = $entityManager->createQueryBuilder();
        $qb->select('t')
            ->from(DailyTasks::class, 't')
            ->where('t.user = :user')
            ->andWhere('t.taskDate BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('start', $start7)
            ->setParameter('end', $end7);

        $tasksLast7Days = $qb->getQuery()->getResult();
        $period = new \DatePeriod($start7, new \DateInterval('P1D'), (clone $end7)->modify('+1 day'));
        $labels = [];
        $values = [];

        foreach ($period as $day) {
            $labels[] = $day->format('d-m');
            $count = 0;
            foreach ($tasksLast7Days as $task) {
                if ($task->getTaskDate()->format('d-m') === $day->format('d-m')) {
                    $count++;
                }
            }
            $values[] = $count;
        }

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

        $startMonth = (new DateTime('first day of this month'))->setTime(0, 0, 0);
        $endMonth = (new DateTime('last day of this month'))->setTime(23, 59, 59);

        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')
            ->from(Expenses::class, 'e')
            ->where('e.user = :user')
            ->andWhere('e.date BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('start', $startMonth)
            ->setParameter('end', $endMonth);

        $expensesThisMonth = $qb->getQuery()->getResult();
        $totalExpenses = [];
        
        $totalExpenses[] = array_reduce($expensesThisMonth, fn($sum, $e) => $sum + $e->getAmount());

        $percentageDone = null;
        $tasksDone = array_filter($tasks, fn($task) => $task->getTaskDone());
        if ($tasks) {
            $percentageDone = (count($tasksDone) / count($tasks) * 100);
        }

        return $this->render('dashboard/dashboard.html.twig', [
            'tasks' => $tasks,
            'tasksLast7Days' => $tasksLast7Days,
            'meetings' => $meetings,
            'totalExpenses' => $totalExpenses,
            'percentage' => $percentageDone,
            'labels' => $labels,
            'values' => $values
        ]);
    }
}
