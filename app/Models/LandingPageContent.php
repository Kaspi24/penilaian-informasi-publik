<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandingPageContent extends Model
{
    use HasFactory;
    // protected $guarded = [];
    protected $fillable = [
        'year',
        'name',
        'nth_sequence',
        'image',
        'is_visible'
    ];
}
