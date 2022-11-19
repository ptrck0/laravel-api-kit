<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory;

    /**
     * @var array
     */
    protected $attributes = [
        'guard_name' => 'web',
    ];
}
