<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            // Change the strict enum to a flexible string
            $table->string('vapt_status')->default('Pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            // Revert back to enum if we ever roll back
            $table->enum('vapt_status', ['Pending', 'In Progress', 'Passed', 'Failed'])->default('Pending')->change();
        });
    }
};
