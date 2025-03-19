<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('activities', function (Blueprint $table) {
        $table->string('request_id')->nullable()->after('request_type'); // Add request_id column
    });
}

public function down()
{
    Schema::table('activities', function (Blueprint $table) {
        $table->dropColumn('request_id'); // Remove request_id column
    });
}
};
