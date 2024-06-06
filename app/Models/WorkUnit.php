<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkUnit extends Model
{
    protected $fillable = [
        'name',
        'category',
        'head_name',
        'phone',
        'email'
    ];
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class,'work_unit_id','id');
    }
}
