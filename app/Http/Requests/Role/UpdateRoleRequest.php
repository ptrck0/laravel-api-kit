<?php

namespace App\Http\Requests\Role;

use App\Rules\PermissionsExist;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [Rule::unique('roles')->ignore($this->route('role')), 'sometimes', 'string'],
            'guard_name' => ['sometimes', 'string'],
            'permissions' => ['array', 'sometimes', new PermissionsExist()],
        ];
    }
}
