<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQueriesTable extends Migration
{
    public function up()
    {
        Schema::create('queries', function (Blueprint $table) {
            $table->foreignId('connection_id')->constrained()->cascadeOnDelete();
            $table->id();
            $table->string('sql');
            $table->string('type', 6);
            $table->json('parameters')->nullable();
            $table->string('button_title');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('queries');
    }
}
