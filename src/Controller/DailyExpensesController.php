<?php

namespace App\Controller;

use App\Entity\Expenses;
use App\Form\BudgetType;
use App\Form\ExpensesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DailyExpensesController extends AbstractController
{
    #[Route('/daily-expenses/{year}/{month}', name: 'app_daily_expenses', defaults: ['year' => null, 'month' => null])]
    public function displayDailyExpenses(Security $security, EntityManagerInterface $entityManager, ?string $year, ?string $month): Response
    {
        if ($year === null || $month === null) {
            $currentYear = date('Y');
            $currentMonth = date('m');
            return $this->redirectToRoute('app_daily_expenses', ['year' => $currentYear, 'month' => $currentMonth]);
        }

        $user = $security->getUser();

        $start = new \DateTime("$year-$month-01 00:00:00");
        $end = (clone $start)->modify('last day of this month')->setTime(23, 59, 59);

        $qb = $entityManager->createQueryBuilder();
        $qb->select('e')
            ->from(Expenses::class, 'e')
            ->where('e.user = :user')
            ->andWhere('e.date BETWEEN :start AND :end')
            ->setParameter('user', $user)
            ->setParameter('start', $start)
            ->setParameter('end', $end);

        $expensesRaw = $qb->getQuery()->getResult();

        $budget = array_sum(array_map(fn($b) => $b->getBudget(), $expensesRaw));
        $expenses = array_filter($expensesRaw, fn($e) => $e->getAmount() !== null);
        $budgetRest = array_sum(array_map(fn($e) => $e->getAmount(), $expenses));
        $restAmount = $budget - $budgetRest;

        return $this->render('daily_expenses/dailyexpenses.html.twig', [
            'budget' => $budget,
            'expenses' => $expenses,
            'budgetRest' => $budgetRest,
            'restAmount' => $restAmount,
            'selectedYear' => $year,
            'selectedMonth' => $month
        ]);
    }

    #[Route('/daily-expenses/{year}/{month}/budget', name: 'app_daily_expenses_budget')]
    public function addBudget(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $budget = new Expenses();
        $form = $this->createForm(BudgetType::class, $budget);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $budget->setUser($user);
            $entityManager->persist($budget);
            $entityManager->flush();

            return $this->redirectToRoute('app_daily_expenses', ['year' => date('Y'), 'month' => date('m')]);
        }

        return $this->render('daily_expenses/budgetform.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/daily-expenses/{year}/{month}/add-expense', name: 'app_daily_expenses_add_expense')]
    public function addExpense(Security $security, Request $request, EntityManagerInterface $entityManager)
    {
        $user = $security->getUser();

        $expense = new Expenses();
        $form = $this->createForm(ExpensesType::class, $expense);
        $form->handleRequest($request);

        $expensesRaw = $entityManager->getRepository(Expenses::class)->findBy(['user' => $user]);
        $budget = array_sum(array_map(fn($b) => $b->getBudget(), $expensesRaw));

        $expenses = [];
        foreach ($expensesRaw as  $v) {
            if ($v->getAmount() !== null) {
                $expenses[] = $v;
            }
        }
        $spent = array_sum(array_map(fn($e) => $e->getAmount(), $expenses));
        $newAmount = $expense->getAmount();

        if ($spent + $newAmount > $budget) {
            $this->addFlash('error', 'This expense would exceed your budget');
            return $this->redirectToRoute('app_daily_expenses', ['year' => date('Y'), 'month' => date('m')]);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $expense->setUser($user);
            $entityManager->persist($expense);
            $entityManager->flush();

            return $this->redirectToRoute('app_daily_expenses', ['year' => date('Y'), 'month' => date('m')]);
        }

        return $this->render('daily_expenses/expenseform.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
