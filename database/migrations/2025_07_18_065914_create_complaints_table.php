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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number');
            $table->unsignedBigInteger('em_id');
            $table->string('type')->nullable();
            $table->string('model')->nullable();
            $table->string('chassis_no')->nullable();
            $table->string('engine_no')->nullable();
            $table->string('colour')->nullable();
            $table->integer('odometer')->nullable();
            $table->tinyInteger('fuel')->nullable(); // 0 - 100 scale
            $table->text('complaint')->nullable();
            $table->date('road_tax_expiry')->nullable();
            $table->date('puspakom_expiry')->nullable();
            $table->date('permit_expiry')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
