<?php

use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respondent_answer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('respondent_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Question::class)->constrained()->cascadeOnDelete();
            $table->boolean('answer')->nullable();
            $table->text('attachment')->nullable();
            $table->float('score')->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('respondent_answer');
    }
};
