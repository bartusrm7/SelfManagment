<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DailyExpensesController extends AbstractController
{
    #[Route('/daily/expenses', name: 'app_daily_expenses')]
    public function index(): Response
    {
        return $this->render('daily_expenses/dailyexpenses.html.twig');
    }
}
