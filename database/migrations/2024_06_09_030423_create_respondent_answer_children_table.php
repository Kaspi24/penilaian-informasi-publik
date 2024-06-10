<?php

use App\Models\Question;
use App\Models\QuestionChildren;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respondent_answer_children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('respondent_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
            $table->foreignId('question_children_id')->references('id')->on('question_children')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Question::class)->constrained()->cascadeOnDelete();
            $table->boolean('answer')->nullable();
            $table->text('attachment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respondent_answer_children');
    }
};
