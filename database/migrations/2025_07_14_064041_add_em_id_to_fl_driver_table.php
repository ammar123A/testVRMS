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
        Schema::table('fl_driver', function (Blueprint $table) {
            $table->string('em_id')->nullable()->after('driver_id');
        });
    }

    public function down()
    {
        Schema::table('fl_driver', function (Blueprint $table) {
            $table->dropColumn('em_id');
        });
    }

};
