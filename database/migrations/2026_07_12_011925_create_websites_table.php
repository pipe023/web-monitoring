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
    Schema::create('websites', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('url');
        $table->enum('status', ['UP', 'DOWN', 'UNKNOWN'])->default('UNKNOWN');
        $table->enum('vapt_status', ['Pending', 'In Progress', 'Passed', 'Failed'])->default('Pending');
        $table->boolean('is_archived')->default(false);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
