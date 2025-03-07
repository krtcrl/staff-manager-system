<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('part_number')->unique(); // Unique part number
            $table->string('part_name'); // Part name
            $table->timestamps(); // Created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('parts');
    }
};
