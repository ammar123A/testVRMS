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
        Schema::create('dv', function (Blueprint $table) {
            $table->string('dv_id')->primary(); // e.g., J01, K15
            $table->string('name'); // e.g., CANSELORI
            $table->boolean('m_st')->default(1); // status (active/inactive)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dv');
    }
};
