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
        Schema::table('online_gateways', function (Blueprint $table) {
            $table->string('mobile_status')->default(0);
            $table->mediumText('mobile_information')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('online_gateways', function (Blueprint $table) {
            $table->dropColumn('mobile_status');
            $table->dropColumn('mobile_information');
        });
    }
};
