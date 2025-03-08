<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->string('attachment')->nullable()->after('description'); // Ensure it's nullable
        });
    }
    
    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('attachment'); // Remove the attachment column
        });
    }
};
