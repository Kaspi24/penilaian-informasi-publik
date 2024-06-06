<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'whatsapp',
        'work_unit_id',
        'role',
        'password',
        'profile_picture',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function work_unit(): BelongsTo
    {
        return $this->belongsTo(WorkUnit::class, 'work_unit_id', 'id');
    }
}
