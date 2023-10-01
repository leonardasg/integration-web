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
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('type');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->boolean('active')->default(true);

            $table->foreign('type')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('date_from');
            $table->dropColumn('date_to');
            $table->dropColumn('active');
        });
    }
};
