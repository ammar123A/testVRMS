<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fl_wo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wr_id');
            $table->string('status')->nullable();
            $table->string('assigned_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fl_wo');
    }
};