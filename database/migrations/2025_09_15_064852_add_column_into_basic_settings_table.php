<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('basic_settings', function (Blueprint $table) {
            $table->string('firebase_admin_json')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('basic_settings', function (Blueprint $table) {
            Schema::dropIfExists('firebase_admin_json');
        });
    }
};
