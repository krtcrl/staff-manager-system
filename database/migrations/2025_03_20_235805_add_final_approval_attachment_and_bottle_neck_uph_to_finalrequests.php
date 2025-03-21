<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('finalrequests', function (Blueprint $table) {
            $table->string('final_approval_attachment')->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('finalrequests', function (Blueprint $table) {
            $table->dropColumn(['final_approval_attachment']);
        });
    }
};
