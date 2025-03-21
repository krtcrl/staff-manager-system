<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('final_request_history', function (Blueprint $table) {
            $table->id();
            $table->string('unique_code')->unique();
            $table->string('part_number');
            $table->string('part_name');

            // Manager statuses
            $table->string('manager_1_status')->default('pending');
            $table->string('manager_2_status')->default('pending');
            $table->string('manager_3_status')->default('pending');
            $table->string('manager_4_status')->default('pending');
            $table->string('manager_5_status')->default('pending');
            $table->string('manager_6_status')->default('pending');

            $table->string('status')->default('pending');
            $table->text('rejection_reason')->nullable();

            // Attachments
            $table->string('final_approval_attachment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_request_history');
    }
};
