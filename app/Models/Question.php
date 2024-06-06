<?php

namespace App\Models;

use App\Models\QuestionChildren;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'text',
        'nth_indicator',
        'category',
        'details',
        'ref_text',
        'ref_address',
        'very_good',
        'good',
        'good_enough',
        'less_good'
    ];

    public function children(): HasMany
    {
        return $this->hasMany(QuestionChildren::class, 'question_id', 'id');
    }
}
