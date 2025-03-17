<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finalrequests', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('unique_code')->unique(); // Unique identifier for the request
            $table->string('part_number'); // Part number
            $table->string('part_name'); // Part name
            $table->string('revision_type')->nullable(); // Revision type
            $table->integer('uph')->nullable(); // Units per hour (UPH)
            $table->text('description')->nullable(); // Description
            $table->string('attachment')->nullable(); // Attachment file path
            $table->string('manager_1_status')->default('pending'); // Manager 1 status
            $table->string('manager_2_status')->default('pending'); // Manager 2 status
            $table->string('manager_3_status')->default('pending'); // Manager 3 status
            $table->string('manager_4_status')->default('pending'); // Manager 4 status
            $table->string('process_type'); // Process type
            $table->integer('current_process_index')->default(0); // Current process index
            $table->integer('total_processes')->default(0); // Total number of processes
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finalrequests');
    }
};
