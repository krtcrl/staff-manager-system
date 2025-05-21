<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('finalrequests', function (Blueprint $table) {
            // Add staff_id column and set up foreign key constraint
            if (!Schema::hasColumn('finalrequests', 'staff_id')) {
                $table->unsignedBigInteger('staff_id')->nullable();
                $table->foreign('staff_id')
                      ->references('id')
                      ->on('staff')
                      ->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('finalrequests', function (Blueprint $table) {
            // Drop foreign key and column
            $table->dropForeign(['staff_id']);
            $table->dropColumn('staff_id');
        });
    }
};