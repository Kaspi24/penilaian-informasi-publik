<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_page_contents', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->string('name');
            $table->smallInteger('nth_sequence');
            $table->string('image');
            $table->boolean('is_visible');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('landing_page_contents');
    }
};
