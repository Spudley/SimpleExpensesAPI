<?php
declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Expense;
use App\Repository\ExpenseRepository;
use App\Tests\BaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;

class ExpenseRepositoryTest extends BaseTestCase
{
    public function testLoadAll()
    {
        //this doesn't prove an awful lot about the correctness of the code,
        //but should flag up if something changes unexpectedly.
        $repo = Mockery::mock(ExpenseRepository::class)->makePartial();
        $repo->shouldReceive('createQueryBuilder')->with('e')->andReturnSelf();
        $repo->shouldReceive('getQuery')->andReturnSelf();
        $repo->shouldReceive('getResult')->andReturn(['foo','bar']);

        $output = $repo->loadAll();

        $this->assertEquals(['foo','bar'], $output);
    }

    public function testCreateExpense()
    {
        \Hamcrest\Util::registerGlobalFunctions();

        $compareExpense = new Expense;
        $compareExpense->setDescription('foo');
        $compareExpense->setValue(123);
        
        $em = Mockery::mock(EntityManagerInterface::class);
        $em->shouldReceive('persist')->with(equalTo($compareExpense));
        $em->shouldReceive('flush');
            
        $repo = $this->createExpenseRepositoryWithInjectedMockEntityManager($em);
        $repo->createExpense('foo', 123);
    }

    /**
     * @dataProvider providerUpdateExpense
     */
    public function testUpdateExpense(string $info, array $input, string $expectedDescription, int $expectedValue)
    {
        \Hamcrest\Util::registerGlobalFunctions();

        $expense = new Expense;
        $expense->setDescription('foo');
        $expense->setValue(111);

        $em = Mockery::mock(EntityManagerInterface::class);
        $em->shouldReceive('persist')->with(sameInstance($expense));
        $em->shouldReceive('flush');

        $repo = $this->createExpenseRepositoryWithInjectedMockEntityManager($em);
        $repo->updateExpense($expense, $input);

        //show that the object data was amended
        $this->assertEquals($expectedDescription, $expense->getDescription());
        $this->assertEquals($expectedValue, $expense->getValue());
    }

    public function providerUpdateExpense()
    {
        return [
            ['Update the description only', ['description'=>'Test'], 'Test', 111],
            ['Update the value only', ['value'=>554], 'foo', 554],
            ['Update both fields', ['description'=>'Test Both', 'value'=>321], 'Test Both', 321],
            ['Invalid description is ignored', ['description'=>999, 'value'=>888], 'foo', 888],
            ['Invalid value is ignored', ['description'=>'New Desc', 'value'=>'New Value'], 'New Desc', 111],
        ];
    }

    public function testDeleteExpense()
    {
        \Hamcrest\Util::registerGlobalFunctions();

        $expense = new Expense;
        $expense->setDescription('foo');
        $expense->setValue(532);

        $em = Mockery::mock(EntityManagerInterface::class);
        $em->shouldReceive('remove')->with(sameInstance($expense));
        $em->shouldReceive('flush');

        $repo = $this->createExpenseRepositoryWithInjectedMockEntityManager($em);
        $repo->deleteExpense($expense);
    }

    private function createExpenseRepositoryWithInjectedMockEntityManager($em)
    {
        //override the constructor so we bypass all the framework stuff we don't need to test.
        return new class($em) extends ExpenseRepository {
            public function __construct($em) {
                $this->mockEntityManager = $em;
            }
            public function getEntityManager() {
                return $this->mockEntityManager;
            }
        };
    }
}
