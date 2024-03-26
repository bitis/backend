<?php

namespace App\Models;

use App\Models\Traits\DefaultDatetimeFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, DefaultDatetimeFormat, SoftDeletes;

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    protected $fillable = ['parent_id', 'name', 'icon', 'path', 'visible', 'type', 'order', 'remark'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany('roles', 'role_menu', 'menu_id', 'role_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id');
    }
}
