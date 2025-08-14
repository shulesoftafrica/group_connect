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
        // Drop existing tables if they exist
        Schema::dropIfExists('user_schools');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('schools');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');

        // Create connect_organizations table
        Schema::create('connect_organizations', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create connect_roles table
        Schema::create('connect_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->json('menu_access')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Create connect_permissions table
        Schema::create('connect_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->string('module');
            $table->string('action');
            $table->timestamps();
        });

        // Create connect_users table
        Schema::create('connect_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('role_id')->nullable()->constrained('connect_roles')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->string('phone')->nullable()->unique();
            $table->string('avatar')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->foreignId('connect_organization_id')->constrained('connect_organizations')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });

        // Create connect_role_permissions table
        Schema::create('connect_role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('connect_roles')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('connect_permissions')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['role_id', 'permission_id']);
        });

        // Create connect_schools table
        Schema::create('connect_schools', function (Blueprint $table) {
            $table->id();
            $table->integer('school_setting_uid')->nullable();
            $table->foreignId('connect_organization_id')->constrained('connect_organizations')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('connect_user_id')->constrained('connect_users')->noActionOnUpdate()->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->string('shulesoft_code')->nullable()->unique();
            $table->json('settings')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('connect_users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['connect_user_id', 'connect_organization_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connect_schools');
        Schema::dropIfExists('connect_role_permissions');
        Schema::dropIfExists('connect_users');
        Schema::dropIfExists('connect_permissions');
        Schema::dropIfExists('connect_roles');
        Schema::dropIfExists('connect_organizations');
    }
};
