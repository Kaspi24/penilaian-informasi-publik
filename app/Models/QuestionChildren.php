<?php

namespace App\Models;

use App\Models\Question;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionChildren extends Model
{
    protected $fillable = [
        'text',
        'question_id'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
