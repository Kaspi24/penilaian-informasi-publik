<?php

use App\Models\Question;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_children', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Question::class)->constrained()->cascadeOnDelete();
            $table->text('text');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_children');
    }
};
