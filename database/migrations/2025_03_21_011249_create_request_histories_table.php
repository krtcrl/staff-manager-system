<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('request_histories', function (Blueprint $table) {
            $table->id();
            $table->string('unique_code')->unique();
            $table->string('part_number');
            $table->text('description')->nullable();
            $table->string('status')->default('completed');
            $table->string('manager_1_status')->nullable();
            $table->string('manager_2_status')->nullable();
            $table->string('manager_3_status')->nullable();
            $table->string('manager_4_status')->nullable();
            $table->string('manager_5_status')->nullable();
            $table->string('manager_6_status')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('request_histories');
    }
};
