<?php

namespace App\Http\Requests\Role;

use App\Rules\PermissionsExist;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['unique:roles', 'required', 'string'],
            'guard_name' => ['sometimes', 'string'],
            'permissions' => ['array', 'sometimes', new PermissionsExist()],
        ];
    }
}
