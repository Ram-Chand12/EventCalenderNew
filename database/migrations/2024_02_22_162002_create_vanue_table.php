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
        Schema::create('vanue', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('vanue_description');
            $table->string('address');
            $table->string('city');
            $table->string('country');
            $table->string('state');
            $table->integer('postal_code');
            $table->string('phone',20);
            $table->string('website');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vanue');
    }
};
