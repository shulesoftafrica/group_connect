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
        // Drop foreign key constraints first, check if they exist
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['role_id']);
            });
        }

        if (Schema::hasTable('user_schools')) {
            Schema::table('user_schools', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropForeign(['school_id']);
            });
        }

        if (Schema::hasTable('role_permissions')) {
            Schema::table('role_permissions', function (Blueprint $table) {
                $table->dropForeign(['role_id']);
                $table->dropForeign(['permission_id']);
            });
        }

        // Drop tables in reverse dependency order
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('user_schools');
        Schema::dropIfExists('schools');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('organizations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is designed to be run before the connect schema migration
        // The down method would recreate the old tables, but we'll leave it empty
        // since we're transitioning to the new schema
    }
};
