<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('finalrequests', function (Blueprint $table) {
            $table->decimal('standard_yield_percentage', 8, 2)->nullable();
            $table->decimal('standard_yield_dollar_per_hour', 10, 2)->nullable();
            $table->decimal('actual_yield_percentage', 8, 2)->nullable();
            $table->decimal('actual_yield_dollar_per_hour', 10, 2)->nullable();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('finalrequests', function (Blueprint $table) {
            //
        });
    }
};
