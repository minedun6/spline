<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePharmaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom');
            $table->string('secteur');
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('note')->nullable();
            $table->string('long')->default('10.1657900');
            $table->string('lat')->default('36.9189700');
            $table->integer('nbre_vitrines')->default(0);
            
            $table->boolean('is_active')->default(true);
            $table->json('extras')->nullable();

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
        Schema::dropIfExists('pharmacies');
    }
}
