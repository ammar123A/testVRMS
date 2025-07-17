<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fm_performed', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wo_id');
            $table->string('description')->nullable();
            $table->string('performed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fm_performed');
    }
};