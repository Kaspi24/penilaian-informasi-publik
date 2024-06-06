<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

class WorkUnit extends Model
{
    use HasFactory;

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
