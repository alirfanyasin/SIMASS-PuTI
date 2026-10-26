<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 50)->nullable()->unique();
            $table->timestamps();
        });

        Schema::create('campus_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('campus_locations')->nullOnDelete();
            $table->string('type', 30); // building, floor, room
            $table->string('name');
            $table->string('code', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('portal_modules', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon', 50)->default('box');
            $table->string('route_name')->nullable();
            $table->string('status', 30)->default('active'); // active, maintenance, coming_soon
            $table->unsignedInteger('order')->default(0);
            $table->json('allowed_roles')->nullable();
            $table->timestamps();
        });

        Schema::create('service_catalogs', function (Blueprint $table) {
            $table->id();
            $table->string('category', 100);
            $table->string('name');
            $table->unsignedInteger('sla_resolution_hours')->default(24);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 50);
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('service_catalogs');
        Schema::dropIfExists('portal_modules');
        Schema::dropIfExists('campus_locations');
        Schema::dropIfExists('units');
    }
};
