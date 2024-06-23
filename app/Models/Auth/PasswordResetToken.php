<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    protected $table = 'password_reset_tokens';
    
    protected $fillable = [
        'email',
        'token',
        'expiry_time'
    ];
}
