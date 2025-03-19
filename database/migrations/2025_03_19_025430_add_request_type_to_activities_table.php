<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('request_type')->nullable()->after('description'); // Add request_type column
        });
    }
    
    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('request_type'); // Remove request_type column
        });
    }
};
