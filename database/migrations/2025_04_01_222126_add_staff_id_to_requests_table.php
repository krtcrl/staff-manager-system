<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up()
{
    Schema::table('requests', function (Blueprint $table) {
        if (!Schema::hasColumn('requests', 'staff_id')) {
            $table->foreignId('staff_id')
                  ->nullable()
                  ->constrained('staff')  // Changed from 'users' to 'staff'
                  ->onDelete('cascade');
        }
    });
}
    

    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->dropColumn('staff_id');
        });
    }
};
