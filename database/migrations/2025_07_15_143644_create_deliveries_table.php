<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('deliveries', function (Blueprint $table) {
        $table->id();
        $table->string('client_name');
        $table->string('address');
        $table->string('city');
        $table->string('postal_code');
        $table->string('time_slot'); // créneau horaire
        $table->float('weight'); // poids du colis
        $table->enum('status', ['en attente', 'en cours', 'livrée'])->default('en attente');
        $table->unsignedBigInteger('user_id')->nullable(false)->change();
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
        Schema::dropIfExists('deliveries');
    }
};
