<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CodePostalCommune extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('code_postal_commune', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('commune_id')->nullable();
            $table->unsignedBigInteger('code_postal_id')->nullable();
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
        //
        Schema::dropIfExists('code_postal_commune');
    }
}
