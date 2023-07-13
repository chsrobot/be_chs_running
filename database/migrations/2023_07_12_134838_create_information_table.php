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
        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('gender');
            $table->string('type_run');
            $table->string('birthdate');
            $table->string('birthmonth');
            $table->string('birthyear');
            $table->string('bibnumber');
            $table->string('phonenumber');
            $table->string('idcard');
            $table->string('healthproblems');
            $table->string('emergencyphone');
            $table->string('emergencontact');
            $table->string('relationship');
            $table->string('bloodtype');
            $table->string('email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('information');
    }
};
