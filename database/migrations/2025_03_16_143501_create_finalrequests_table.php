<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalrequestsTable extends Migration
{
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
            $table->string('part_number'); // Part number of the request
            $table->text('description')->nullable(); // Description of the request
            $table->string('process_type'); // Process type of the request
            $table->integer('current_process_index'); // Current process index
            $table->integer('total_processes'); // Total number of processes
            $table->string('manager_1_status')->default('approved'); // Manager 1 status (default to approved)
            $table->string('manager_2_status')->default('approved'); // Manager 2 status (default to approved)
            $table->string('manager_3_status')->default('approved'); // Manager 3 status (default to approved)
            $table->string('manager_4_status')->default('approved'); // Manager 4 status (default to approved)
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
}