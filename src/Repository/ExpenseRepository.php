<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Expense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Expense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expense[]    findAll()
 * @method Expense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expense::class);
    }

    public function loadAll()
    {
        return $this->createQueryBuilder('e')
            ->getQuery()
            ->getResult()
        ;
    }

    public function createExpense(string $description, int $value): Expense
    {
        $expense = new Expense();

        $expense
            ->setDescription($description)
            ->setValue($value);

        $this->getEntityManager()->persist($expense);
        $this->getEntityManager()->flush();
        
        return $expense;
    }

    public function updateExpense(Expense $expense, array $fields): Expense
    {
        if (isset($fields['description']) && is_string($fields['description'])) {
            $expense->setDescription($fields['description']);
        }
        if (isset($fields['value']) && is_int($fields['value'])) {
            $expense->setValue($fields['value']);
        }

        $this->getEntityManager()->persist($expense);
        $this->getEntityManager()->flush();

        return $expense;
    }

    public function deleteExpense(Expense $expense)
    {
        $this->getEntityManager()->remove($expense);
        $this->getEntityManager()->flush();
    }
}
