<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormQueryTable extends Migration
{
    public function up()
    {
        Schema::create('form_query', function (Blueprint $table) {
            $table->foreignId('form_id')->constrained()->cascadeOnDelete();
            $table->foreignId('query_id')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('form_query');
    }
}
