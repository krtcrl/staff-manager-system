<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->string('final_approval_attachment')->nullable()->after('description');
        });
    }
    
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('final_approval_attachment');
        });
    }
    
};
