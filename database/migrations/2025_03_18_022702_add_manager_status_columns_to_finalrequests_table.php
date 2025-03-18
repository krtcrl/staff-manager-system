<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('finalrequests', function (Blueprint $table) {
        $table->string('manager_1_status')->default('pending');
        $table->string('manager_2_status')->default('pending');
        $table->string('manager_3_status')->default('pending');
        $table->string('manager_4_status')->default('pending');
        $table->string('manager_5_status')->default('pending');
        $table->string('manager_6_status')->default('pending');
    });
}

public function down()
{
    Schema::table('finalrequests', function (Blueprint $table) {
        $table->dropColumn([
            'manager_1_status',
            'manager_2_status',
            'manager_3_status',
            'manager_4_status',
            'manager_5_status',
            'manager_6_status',
        ]);
    });
}
};
