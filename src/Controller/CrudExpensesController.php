<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Expense;
use App\Repository\ExpenseRepository;
use App\Service\ExpenseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CrudExpensesController extends AbstractController
{
    private $expenseRepository;
    private $expenseService;

    public function __construct(ExpenseRepository $expenseRepository, ExpenseService $expenseService)
    {
        $this->expenseRepository = $expenseRepository;
        $this->expenseService = $expenseService;
    }

    /**
     * @Route("/expenses/", name="add_expense", methods={"POST"})
     */
    public function addExpense(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$this->expenseService->inputHasValidDescription($data)) {
            throw new BadRequestHttpException('Description must be provided.');
        }
        if (!$this->expenseService->inputHasValidValue($data)) {
            throw new BadRequestHttpException('Value must be a positive integer.');
        }

        $this->expenseRepository->createExpense($data['description'], $data['value']);

        return $this->json(['status'=>'success', 'message'=>'Expense record created']);
    }

    /**
     * @Route("/expenses/{id}", name="get_expense", methods={"GET"})
     */
    public function getExpense(int $id): JsonResponse
    {
        $expense = $this->findbyId($id);

        return $this->json(['status' => 'success', 'data' => $expense]);
    }

    /**
     * @Route("/expenses/{id}", name="update_expense", methods={"PATCH"})
     */
    public function updateExpense(int $id, Request $request): JsonResponse
    {
        $expense = $this->findbyId($id);

        $data = json_decode($request->getContent(), true);

        $fields = [];
        if ($this->expenseService->inputHasValidDescription($data)) {
            $fields['description'] = $data['description'];
        }
        if ($this->expenseService->inputHasValidValue($data)) {
            $fields['value'] = $data['value'];
        }

        if (!count($fields)) {
            throw new BadRequestHttpException('At least one valid field must be provided to update.');
        }

        $this->expenseRepository->updateExpense($expense, $fields);

        return $this->json(['status' => 'success', 'data' => $expense]);
    }

    /**
     * @Route("/expenses/{id}", name="delete_expense", methods={"DELETE"})
     */
    public function deleteExpense(int $id): JsonResponse
    {
        $expense = $this->findById($id);
        $this->expenseRepository->deleteExpense($expense);

        return $this->json(['status' => 'success', 'message' => 'Expense deleted']);
    }

    protected function findbyId(int $id): Expense
    {
        $expense = $this->expenseRepository->findOneBy(['id' => $id]);
        if (!$expense) {
            throw new NotFoundHttpException("Unknown record ID {$id} requested.");
        }
        return $expense;
    }
}
