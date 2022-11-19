<?php

namespace App\Http\Requests\User;

use App\Rules\PermissionsExist;
use App\Rules\RolesExist;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'username' => ['string', 'unique:users', 'required'],
            'password' => ['required', Password::defaults()],
            'email' => ['required', 'unique:users'],
            'roles' => ['array', 'sometimes', new RolesExist()],
            'permissions' => ['array', 'sometimes', new PermissionsExist()],
        ];
    }
}
