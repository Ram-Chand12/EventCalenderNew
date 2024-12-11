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
        Schema::table('wordpress_reference', function (Blueprint $table) {
            $table->string('no_of_tries')->nullable();
            $table->boolean('verified')->default(false);
             
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wordpress_reference', function (Blueprint $table) {
            $table->dropColumn('no_of_tries');
        });
    }
};