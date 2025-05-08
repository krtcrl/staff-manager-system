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
        Schema::create('request_processes', function (Blueprint $table) {
            $table->id();
            $table->string('unique_code'); // Reference to the request
            $table->string('part_number'); // The part number
            $table->string('process_type');
            $table->integer('process_order');
            $table->string('status')->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->foreign('unique_code')->references('unique_code')->on('requests');
            $table->foreign('part_number')->references('part_number')->on('parts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_process');
    }
};
