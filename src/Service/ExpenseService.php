<?php
declare(strict_types=1);

namespace App\Service;

class ExpenseService
{
    public function inputHasValidDescription(array $input)
    {
        if (!isset($input['description'])) {
            return false;
        }
        if (!is_string($input['description']) || !$input['description']) {
            return false;
        }
        return true;
    }

    public function inputHasValidValue(array $input)
    {
        if (!isset($input['value'])) {
            return false;
        }
        if (!is_int($input['value']) || $input['value'] <= 0) {
            return false;
        }
        return true;
    }
}
