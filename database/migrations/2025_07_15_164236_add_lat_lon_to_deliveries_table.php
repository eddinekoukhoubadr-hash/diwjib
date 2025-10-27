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
    Schema::table('deliveries', function (Blueprint $table) {
        $table->decimal('latitude', 10, 7)->nullable()->after('postal_code');
        $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
    });
}

public function down()
{
    Schema::table('deliveries', function (Blueprint $table) {
        $table->dropColumn(['latitude', 'longitude']);
    });
}


};
