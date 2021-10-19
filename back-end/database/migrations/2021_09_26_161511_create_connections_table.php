<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnectionsTable extends Migration
{
    public function up()
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->id();
            $table->string('host');
            $table->unsignedMediumInteger('port');
            $table->string('database');
            $table->string('username');
            $table->string('password')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('connections');
    }
}
