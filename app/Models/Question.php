<?php

namespace App\Models;

use App\Models\QuestionChildren;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function respondents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'respondent_answer', 'question_id', 'respondent_id', 'id', 'id')
            ->withPivot('answer', 'attachment', 'score', 'updated_by', 'updated_by_name');
    }
}
