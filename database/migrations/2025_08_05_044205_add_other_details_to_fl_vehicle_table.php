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
            $table->string('engine_no')->nullable()->after('chassis_no');
            $table->string('colour')->nullable()->after('engine_no');
            $table->string('road_tax_expiry')->nullable()->after('road_tax_expiry');
            $table->string('puspakom_expiry')->nullable()->after('puspakom_expiry');
            $table->string('permit_expiry')->nullable()->after('permit_expiry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fl_vehicle', function (Blueprint $table) {
            //
        });
    }
};
