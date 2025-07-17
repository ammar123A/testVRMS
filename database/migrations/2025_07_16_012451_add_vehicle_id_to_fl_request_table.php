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
        Schema::table('fl_request', function (Blueprint $table) {
            $table->unsignedBigInteger('vehicle_id')->nullable()->after('driver_id'); 

            // Optional: Add FK constraint if `fl_vehicle.id` exists
            $table->foreign('vehicle_id')->references('vehicle_id')->on('fl_vehicle')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fl_request', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropColumn('vehicle_id');
        });
    }
};
