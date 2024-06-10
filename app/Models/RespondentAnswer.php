<?php

namespace App\Models;

use App\Models\User;
use App\Models\Question;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RespondentAnswer extends Model
{
    protected $table = 'respondent_answer';
    
    protected $guarded = ['score'];

    public function respondent(): BelongsTo
    {
        return $this->belongsTo(User::class,'respondent_id','id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class,'question_id','id');
    }
}
