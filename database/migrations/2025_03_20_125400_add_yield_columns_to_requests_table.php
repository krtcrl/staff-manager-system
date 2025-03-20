<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYieldColumnsToRequestsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // Add columns for standard yield and actual yield
            $table->decimal('standard_yield_percentage', 5, 2)->nullable()->after('uph'); // Percentage (e.g., 95.50)
            $table->decimal('standard_yield_dollar_per_hour', 10, 2)->nullable()->after('standard_yield_percentage'); // $/hr (e.g., 12.34)
            $table->decimal('actual_yield_percentage', 5, 2)->nullable()->after('standard_yield_dollar_per_hour'); // Percentage (e.g., 90.00)
            $table->decimal('actual_yield_dollar_per_hour', 10, 2)->nullable()->after('actual_yield_percentage'); // $/hr (e.g., 10.50)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // Drop the columns if the migration is rolled back
            $table->dropColumn([
                'standard_yield_percentage',
                'standard_yield_dollar_per_hour',
                'actual_yield_percentage',
                'actual_yield_dollar_per_hour',
            ]);
        });
    }
}