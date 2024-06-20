<?php

namespace App\Models;

use App\Models\User;
use App\Models\Question;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RespondentAnswer extends Model
{
    protected $table = 'respondent_answer';
    
    protected $fillable = [
        'respondent_id',
        'question_id',
        'answer',
        'attachment',
        'score',
        'updated_by',
        'updated_by_name',
    ];

    public function respondent(): BelongsTo
    {
        return $this->belongsTo(User::class,'respondent_id','id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class,'question_id','id');
    
    }

    public function answer_children(): HasMany
    {
        return $this->hasMany(RespondentAnswerChildren::class,'respondent_answer_id','id');
    }
}
