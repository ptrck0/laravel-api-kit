<?php

namespace App\Http\Requests\Permission;

use App\Rules\RolesExist;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [Rule::unique('permissions')->ignore($this->route('permission')), 'sometimes', 'string'],
            'guard_name' => ['sometimes', 'string'],
            'roles' => ['array', 'sometimes', new RolesExist()],
        ];
    }
}
