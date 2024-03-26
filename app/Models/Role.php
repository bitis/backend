<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends \Spatie\Permission\Models\Role
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at', 'pivot'];

    protected $fillable = ['name', 'guard_name', 'company_id', 'show_name', 'remark', 'status'];

    protected $guard_name = 'api';
}
