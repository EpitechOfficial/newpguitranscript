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
        Schema::table('courier', function (Blueprint $table) {
            $table->unsignedBigInteger('trans_details_id')->nullable()->after('date');
            $table->foreign('trans_details_id')->references('id')->on('trans_details_new')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courier', function (Blueprint $table) {
            $table->dropForeign(['trans_details_id']);
            $table->dropColumn('trans_details_id');
        });
    }
}; 