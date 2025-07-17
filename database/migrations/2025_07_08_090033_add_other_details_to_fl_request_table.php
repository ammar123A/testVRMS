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
        // Add only new fields here
        $table->string('attention_to')->nullable();
        $table->string('vote_ptj')->nullable();
        $table->string('dept_faculty')->nullable();
        $table->string('officer_email')->nullable();
        $table->string('vehicle_request')->nullable();
        $table->tinyInteger('no_vehicle')->default(1);
        $table->decimal('estimated_cost', 10, 2)->nullable();

        $table->string('program')->nullable();
        $table->string('booking_type')->nullable();
        $table->text('pickup_point')->nullable();
        $table->string('pickup_state')->nullable();
        $table->text('destination')->nullable();
        $table->string('destination_state')->nullable();
        $table->string('remark')->nullable();

        $table->date('start_date')->nullable();
        $table->time('start_time')->nullable();
        $table->date('end_date')->nullable();
        $table->time('end_time')->nullable();

        $table->boolean('agree')->default(false);
    });
}

public function down(): void
{
    Schema::table('fl_request', function (Blueprint $table) {
        $table->dropColumn([
            'attention_to', 'vote_ptj', 'dept_faculty', 'officer_email',
            'vehicle_request', 'no_vehicle', 'estimated_cost',
            'program', 'booking_type', 'pickup_point', 'pickup_state',
            'destination', 'destination_state', 'remark',
            'start_date', 'start_time', 'end_date', 'end_time',
            'agree'
        ]);
    });
}

};
