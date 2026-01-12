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
    #[Route('/daily-expenses', name: 'app_daily_expenses')]
    public function displayDailyExpenses(Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();
        $budgetRaw = $entityManager->getRepository(Expenses::class)->findBy(['user' => $user]);
        $budget = array_sum(array_map(fn($b) => $b->getBudget(), $budgetRaw));

        return $this->render('daily_expenses/dailyexpenses.html.twig', [
            'budget' => $budget
        ]);
    }

    #[Route('/daily-expenses/budget', name: 'app_daily_expenses_budget')]
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

            return $this->redirectToRoute('app_daily_expenses');
        }

        return $this->render('daily_expenses/budgetform.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/daily-expenses/add-expense', 'app_daily_expenses_add_expense')]
    public function addExpense(Request $request, EntityManagerInterface $entityManager)
    {
        $expense = new Expenses();
        $form = $this->createForm(ExpensesType::class, $expense);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($expense);
            $entityManager->flush();
        }

        return $this->render('daily_expenses/expenseform.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
