<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBreedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breeds', function (Blueprint $table) {
            $table->increments('id_breed');
            $table->string('id')->unique();
            $table->string('name');
            $table->string('description');
            $table->string('temperament');
            $table->string('life_span');
            $table->string('alt_names');
            $table->string('wikipedia_url');
            $table->string('origin');
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
        Schema::dropIfExists('breeds');
    }
}
