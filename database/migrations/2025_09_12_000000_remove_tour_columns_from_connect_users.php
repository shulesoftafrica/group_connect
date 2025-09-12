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
        Schema::table('connect_users', function (Blueprint $table) {
            $table->dropColumn(['tour_completed_at', 'tour_steps_completed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('connect_users', function (Blueprint $table) {
            $table->timestamp('tour_completed_at')->nullable();
            $table->json('tour_steps_completed')->nullable();
        });
    }
};
