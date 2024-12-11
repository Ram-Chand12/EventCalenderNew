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
        Schema::create('golf_group', function (Blueprint $table) {
            $table->id();            
            $table->string('gname');
            $table->string('image')->nullable();
            $table->boolean('status');
            $table->timestamps();
           


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('golf_group');
    }
};
