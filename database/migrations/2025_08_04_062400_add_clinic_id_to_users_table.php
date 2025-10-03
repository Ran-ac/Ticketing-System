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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('branch')->nullable()->after('id'); // add branch column

            // Add foreign key constraint on branch column (not clinic_id)
            $table->foreign('branch')->references('id')->on('clinics')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch']);  // drop foreign key on branch
            $table->dropColumn('branch');     // drop branch column
        });
    }
};
