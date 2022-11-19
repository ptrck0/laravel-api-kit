<?php

namespace App\Rules;

use App\Models\Permission;
use Closure;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class PermissionsExist implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail): void
    {
        if (!is_array($value)) {
            $fail('The :attribute must be an array');
        }

        if (!array_consists_of_integers($value)) {
            $fail('The list can only contain integers');
        }

        if (Permission::findMany($value)->count() !== count($value)) {
            $fail('Not all :attribute exist');
        }
    }
}
