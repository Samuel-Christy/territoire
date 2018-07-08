<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRadarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->string('type')->nullable();
            $table->string('labelType')->nullable();
            $table->float('lat')->nullable();
            $table->float('lng')->nullable();
            $table->longText('json')->nullable();
            //addendum
            $table->date('radarInstallDate')->nullable();
            $table->string('radarDirection')->nullable();
            $table->string('radarEquipment')->nullable();
            $table->string('radarPlace')->nullable();
            $table->string('radarRoad')->nullable();


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
        Schema::dropIfExists('radars');
    }
}
