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
            $table->string('transcript_purpose')->nullable()->after('trans_details_id');
            $table->integer('number_of_copies')->default(1)->after('transcript_purpose');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courier', function (Blueprint $table) {
            $table->dropColumn(['transcript_purpose', 'number_of_copies']);
        });
    }
}; 