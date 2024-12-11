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
        Schema::create('syncer_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('wrd_id')->default(0); 
            $table->integer('club_id')->default(0); 
            $table->integer('ref_id')->default(0); 
            $table->string('entity_type')->default('');
            $table->string('message_type')->default('');
            $table->text('message')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syncer_logs');
    }
};
