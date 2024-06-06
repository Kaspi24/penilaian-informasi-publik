<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->string('nth_indicator');
            $table->string('category');
            $table->string('details')->nullable();
            $table->string('ref_text')->nullable();
            $table->string('ref_address')->nullable();
            $table->float('very_good');
            $table->float('good');
            $table->float('good_enough');
            $table->float('less_good');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
