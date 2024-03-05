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
        Schema::create('symptoms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('question', 200);
            $table->boolean('is_diabetes_type_1');
            $table->boolean('is_diabetes_type_2');
            $table->float('probability', unsigned: true);
            $table->float('plausability', unsigned: true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('symptoms');
    }
};
