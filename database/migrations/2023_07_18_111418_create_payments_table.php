<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_evidences', function (Blueprint $table) {
            $table->id();
            $table->string('email_uploader');
            $table->string('picture_path');
            $table->enum('status', ['waiting','pending', 'checked'])->default('pending');
            $table->string('email_checker')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_evidences');
    }
};
