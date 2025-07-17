<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fl_sms', function (Blueprint $table) {
            $table->id();
            $table->string('recipient')->nullable();
            $table->string('message')->nullable();
            $table->string('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fl_sms');
    }
};