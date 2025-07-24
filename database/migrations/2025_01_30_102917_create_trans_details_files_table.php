<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('trans_details_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('trans_details_id'); // Match INT type
            $table->string('file_path');
            $table->timestamps();
        
            $table->foreign('trans_details_id')
                  ->references('id')
                  ->on('trans_details_new')
                  ->onDelete('cascade');
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('trans_details_files');
    }
};
