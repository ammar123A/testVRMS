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
        Schema::table('fl_leave', function (Blueprint $table) {
            $table->renameColumn('driver_id', 'em_id');
            $table->string('name')->nullable()->after('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fl_leave', function (Blueprint $table) {
            //
        });
    }
};
