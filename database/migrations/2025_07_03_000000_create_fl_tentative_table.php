<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fl_tentative', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->date('start_time')->nullable();
            $table->date('end_time')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fl_tentative');
    }
};