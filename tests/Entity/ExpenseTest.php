<?php
declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Expense;
use App\Tests\BaseTestCase;

class ExpenseTest extends BaseTestCase
{
    public function testJson()
    {
        $obj = new Expense();
        $obj->setDescription('foo');
        $obj->setValue(1234);
        $output = json_encode($obj);
        $expectedOutput = '{"id":null,"description":"foo","value":1234}';
        $this->assertEquals($expectedOutput, $output);
    }
}
