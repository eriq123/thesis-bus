<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('conductor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('starting_point_id')->constrained('bus_routes')->onDelete('cascade');
            $table->foreignId('destination_id')->constrained('bus_routes')->onDelete('cascade');
            $table->integer('fare');
            $table->string('schedule_date');
            $table->string('time_departure');
            $table->string('time_arrival');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
