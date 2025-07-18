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
        Schema::create('fl_commpany', function (Blueprint $table) {
            $table->id('comp_id');
            $table->string('name');
            $table->string('roc')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('tel')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->text('address')->nullable();
            $table->string('state')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fl_commpany');
    }
};
