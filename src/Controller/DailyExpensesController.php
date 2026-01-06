<?php

namespace App\Controller;

use App\Entity\Expenses;
use App\Form\BudgetType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DailyExpensesController extends AbstractController
{
    #[Route('/daily-expenses', name: 'app_daily_expenses')]
    public function index(): Response
    {
        return $this->render('daily_expenses/dailyexpenses.html.twig');
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
}
