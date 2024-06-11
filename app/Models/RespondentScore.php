<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RespondentScore extends Model
{
    protected $table = 'respondent_score';
    
    protected $guarded = ['total_score'];

    public function respondent(): BelongsTo
    {
        return $this->belongsTo(User::class,'respondent_id','id');
    }

    public function jury(): BelongsTo
    {
        return $this->belongsTo(User::class,'jury_id','id');
    }
}
