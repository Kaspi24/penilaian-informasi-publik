<?php

namespace App\Models;

use App\Models\User;
use App\Models\Question;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RespondentAnswer extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

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

    protected $auditInclude = [
        'score'
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

    // public function audits(): HasMany
    // {
    //     return $this->hasMany(Audit::class, 'auditable_id')->where('auditable_type', self::class);
    // }
}
