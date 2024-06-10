<?php

namespace App\Models;

use App\Models\Question;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class QuestionChildren extends Model
{
    protected $table = 'question_children';

    protected $fillable = [
        'text',
        'question_id'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function respondents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'respondent_answer_children', 'question_children_id', 'respondent_id', 'id', 'id')
            ->withPivot('answer', 'attachment', 'question_id');
    }
}
