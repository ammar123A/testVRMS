<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('fl_request', function (Blueprint $table) {
            $table->unsignedBigInteger('driver_id')->nullable()->after('request_id');

            // Add foreign key constraint
            $table->foreign('driver_id')
                  ->references('driver_id')
                  ->on('fl_driver')
                  ->onDelete('set null'); // optional: can be 'cascade' or 'restrict'
        });
    }

    public function down()
    {
        Schema::table('fl_request', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn('driver_id');
        });
    }
};
