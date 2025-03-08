<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->string('revision_type')->nullable()->after('process_type'); // Add revision_type column
        });
    }
    
    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('revision_type'); // Rollback the column
        });
    }
};
