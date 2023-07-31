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
        Schema::create('others_informations', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('idcard');
            $table->string('name');
            $table->string('lastname');
            $table->string('gender');
            $table->string('type_run');
            $table->string('size');
            $table->string('birthdate');
            $table->string('bibnumber')->nullable();
            $table->string('phonenumber');
            $table->string('healthproblems');
            $table->string('emergencyphone');
            $table->string('emergencontact');
            $table->string('relationship');
            $table->string('bloodtype');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('others_informations');
    }
};
