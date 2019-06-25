<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeleguePharamcieClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_pharmacie_delegue', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('pharmacie_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('delegue_id')->nullable();

            $table->json('extras')->nullable();
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
        Schema::dropIfExists('client_pharmacie_delegue');
    }
}
