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
        Schema::create('fl_allocation', function (Blueprint $table) {
            $table->id('altn_id');
            $table->year('altn_year');
            $table->string('altn_phb');
            $table->string('altn_category'); 
            $table->decimal('altn_allocation', 15, 2);
            $table->date('altn_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fl_allocation');
    }
};
