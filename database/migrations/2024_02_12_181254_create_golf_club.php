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
        Schema::create('golf_club', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('password');
            $table->string('salt')->nullable();
            $table->string('url');
            $table->string('group_name');
            $table->boolean('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('golf_club');
    }
};
