<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeEntriesTable extends Migration
{
    public function up()
    {
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            $table->time('clock_in');
            $table->time('clock_in_dispensation');
            $table->time('clock_out');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('time_entries');
    }
}