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
        Schema::create('demo_requests', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name');
            $table->string('organization_contact');
            $table->string('contact_name');
            $table->string('contact_phone');
            $table->string('contact_email');
            $table->text('organization_address');
            $table->string('organization_country');
            $table->integer('total_schools');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('approval_token')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->json('credentials')->nullable(); // Store generated username and password
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_requests');
    }
};
