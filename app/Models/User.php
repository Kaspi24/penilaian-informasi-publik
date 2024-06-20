<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\WorkUnit;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'whatsapp',
        'work_unit_id',
        'role',
        'password',
        'profile_picture',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function work_unit(): BelongsTo
    {
        return $this->belongsTo(WorkUnit::class, 'work_unit_id', 'id');
    }

    public function answers(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'respondent_answer', 'respondent_id', 'question_id', 'id', 'id')
            ->withPivot('answer', 'attachment', 'score', 'updated_by', 'updated_by_name');
    }

    public function answer_children(): BelongsToMany
    {
        return $this->belongsToMany(QuestionChildren::class, 'respondent_answer_children', 'respondent_id', 'question_children_id', 'id', 'id')
            ->withPivot('answer', 'attachment', 'question_id');
    }

    public function score(): HasOne
    {
        return $this->hasOne(RespondentScore::class, 'respondent_id', 'id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(RespondentScore::class, 'jury_id', 'id');
    }
}
