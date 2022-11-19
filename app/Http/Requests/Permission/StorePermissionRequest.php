<?php

namespace App\Http\Requests\Permission;

use App\Rules\RolesExist;
use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['unique:permissions', 'required', 'string'],
            'guard_name' => ['sometimes', 'string'],
            'roles' => ['array', 'sometimes', new RolesExist()],
        ];
    }
}
