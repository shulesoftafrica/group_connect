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
        Schema::table('connect_schools', function (Blueprint $table) {
            $table->dropUnique(['connect_user_id', 'connect_organization_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('connect_schools', function (Blueprint $table) {
            $table->unique(['connect_user_id', 'connect_organization_id']);
        });
    }
};
