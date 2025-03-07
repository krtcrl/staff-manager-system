<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('requests', function (Blueprint $table) {
        // Add manager-specific status columns
        $table->string('manager_1_status')->default('pending')->after('description');
        $table->string('manager_2_status')->default('pending')->after('manager_1_status');
        $table->string('manager_3_status')->default('pending')->after('manager_2_status');
        $table->string('manager_4_status')->default('pending')->after('manager_3_status');
    });
}

public function down()
{
    Schema::table('requests', function (Blueprint $table) {
        // Drop the manager-specific status columns
        $table->dropColumn(['manager_1_status', 'manager_2_status', 'manager_3_status', 'manager_4_status']);
    });
}
};
