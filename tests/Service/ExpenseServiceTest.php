<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Expense;
use App\Service\ExpenseService;
use App\Tests\BaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Mockery;

class ExpenseServiceTest extends BaseTestCase
{
    /**
     * @dataProvider providerInputHasValidDescription
     */
    public function testInputHasValidDescription($input, $expectedOutput)
    {
        $obj = new ExpenseService;
        $output = $obj->inputHasValidDescription($input);
        $this->assertEquals($expectedOutput, $output);
    }

    public function providerInputHasValidDescription()
    {
        return [
            [['description'=>'this is valid', 'value'=>123], true],
            [['description'=>'this is valid', 'value'=>-123], true],
            [['description'=>'this is valid', 'value'=>'123'], true],
            [['description'=>'this is valid', 'value'=>0], true],
            [['description'=>'this is valid', 'value'=>null], true],
            [['description'=>'this is valid'], true],
            [['description'=>'this is valid', 'value'=>123, 'anotherField'=>'foo'], true],

            [['description'=>'', 'value'=>123], false],
            [['description'=>null, 'value'=>123], false],
            [['value'=>123], false],
            [[], false],
        ];
    }

    /**
     * @dataProvider providerInputHasValidValue
     */
    public function testInputHasValidValue($input, $expectedOutput)
    {
        $obj = new ExpenseService;
        $output = $obj->inputHasValidValue($input);
        $this->assertEquals($expectedOutput, $output);
    }

    public function providerInputHasValidValue()
    {
        return [
            [['description'=>'this is valid', 'value'=>123], true],
            [['description'=>'this is valid', 'value'=>-123], false],
            [['description'=>'this is valid', 'value'=>'123'], false],
            [['description'=>'this is valid', 'value'=>0], false],
            [['description'=>'this is valid', 'value'=>null], false],
            [['description'=>'this is valid'], false],
            [['description'=>'this is valid', 'value'=>123, 'anotherField'=>'foo'], true],

            [['description'=>'', 'value'=>123], true],
            [['description'=>null, 'value'=>123], true],
            [['value'=>123], true],
            [[], false],
        ];
    }
}
