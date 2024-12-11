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
        Schema::create('wordpress_reference', function (Blueprint $table) {
            $table->id();
            $table->string('user_name')->default(''); 
            $table->integer('wrd_id')->default(0); 
            $table->integer('club_id')->default(0); 
            $table->integer('ref_id')->default(0); 
            $table->string('entity_type')->default('');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wrd_users');
    }
};
