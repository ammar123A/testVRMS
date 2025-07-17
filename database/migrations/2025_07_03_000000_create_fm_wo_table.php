<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fm_wo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wr_id');
            $table->string('status')->nullable();
            $table->string('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fm_wo');
    }
};