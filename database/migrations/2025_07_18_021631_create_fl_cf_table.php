<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fl_cf', function (Blueprint $table) {
            $table->id();
            $table->string('em_id')->unique();
            $table->string('em_number')->nullable();
            $table->string('gred')->nullable();
            $table->string('position')->nullable();
            $table->string('dv_name')->nullable();
            $table->string('site_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('user_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fl_cf');
    }
};
