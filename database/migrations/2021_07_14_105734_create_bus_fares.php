<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusFares extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bus_fares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('starting_point')->constrained('bus_routes')->onDelete('cascade');
            $table->foreignId('destination')->constrained('bus_routes')->onDelete('cascade');
            $table->string('fare_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bus_fares');
    }
}
