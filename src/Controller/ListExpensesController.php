<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\ExpenseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ListExpensesController extends AbstractController
{
    /**
     * @Route("/listExpenses")
     */
    public function listExpenses(ExpenseRepository $expenseRepository): JsonResponse
    {
        $expenses = $expenseRepository->loadAll();
        return $this->json(['status' => 'success', 'data' => $expenses]);
    }
}

