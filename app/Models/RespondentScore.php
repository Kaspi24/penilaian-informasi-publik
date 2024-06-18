<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RespondentScore extends Model
{
    protected $table = 'respondent_score';
    
    protected $fillable = [
        'respondent_id',
        'jury_id',
        'is_done_filling',
        'is_done_scoring',
        'total_score',
        'updated_by',
        'updated_by_name',
    ];

    public function respondent(): BelongsTo
    {
        return $this->belongsTo(User::class,'respondent_id','id');
    }

    public function jury(): BelongsTo
    {
        return $this->belongsTo(User::class,'jury_id','id');
    }
}
