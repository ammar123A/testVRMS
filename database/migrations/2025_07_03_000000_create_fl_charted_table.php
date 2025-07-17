<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fl_charted', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wo_id');
            $table->unsignedBigInteger('provider_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fl_charted');
    }
};