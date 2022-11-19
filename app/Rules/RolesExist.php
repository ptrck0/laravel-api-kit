<?php

namespace App\Rules;

use App\Models\Role;
use Illuminate\Contracts\Validation\InvokableRule;

class RolesExist implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (!is_array($value)) {
            $fail('The :attribute must be an array');
        }

        if (!array_consists_of_integers($value)) {
            $fail('The list can only contain integers');
        }

        if (Role::findMany($value)->count() !== count($value)) {
            $fail('Not all :attribute exist');
        }
    }
}
