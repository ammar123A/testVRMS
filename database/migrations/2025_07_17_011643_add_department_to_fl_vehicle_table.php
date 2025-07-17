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
        Schema::table('fl_vehicle', function (Blueprint $table) {
            $table->string('department')->nullable()->after('vehicle_request');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fl_vehicle', function (Blueprint $table) {
            $table->dropColumn('department');
        });
    }
};
