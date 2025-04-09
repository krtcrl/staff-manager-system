<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('request_histories', function (Blueprint $table) {
            $table->string('part_name')->nullable()->after('part_number');
        });
    }
    
    public function down()
    {
        Schema::table('request_histories', function (Blueprint $table) {
            $table->dropColumn('part_name');
        });
    }
};
