<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->string('reset_token')->nullable()->after('password');
            $table->timestamp('reset_token_created_at')->nullable()->after('reset_token');
        });

        Schema::table('managers', function (Blueprint $table) {
            $table->string('reset_token')->nullable()->after('password');
            $table->timestamp('reset_token_created_at')->nullable()->after('reset_token');
        });
    }

    public function down()
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['reset_token', 'reset_token_created_at']);
        });

        Schema::table('managers', function (Blueprint $table) {
            $table->dropColumn(['reset_token', 'reset_token_created_at']);
        });
    }
};