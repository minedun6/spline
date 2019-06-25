<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommandesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date_pose')->nullable();
            $table->string('description')->nullable();
            $table->string('status');

            $table->string('type_support')->nullable();
            $table->json('vitrine')->nullable();
            
            $table->boolean('is_vitrine');
            $table->boolean('is_presentoir');
            $table->boolean('is_merchandising');

            $table->integer('pharmacie_id')->nullable();
            $table->integer('owner_id');
            $table->integer('client_id');
            $table->integer('product_id')->nullable();
            $table->integer('planification_id')->nullable();
            
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
        Schema::dropIfExists('commandes');
    }
}
