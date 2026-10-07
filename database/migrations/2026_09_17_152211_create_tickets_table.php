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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 50)->unique()->index();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('category', 100)->index();
            $table->string('service_item', 150)->index();
            $table->string('authority', 20)->default('local'); // local, central
            $table->string('scope', 30)->default('internal_surabaya'); // internal_surabaya, needs_clarification, escalated_central
            $table->string('status', 30)->default('open')->index(); // open, pending_user, in_progress, waiting_central, resolved, closed
            $table->string('priority', 20)->default('medium'); // low, medium, high, urgent
            $table->string('location')->nullable();
            $table->string('title');
            $table->text('description');
            $table->string('attachment')->nullable();
            $table->string('central_ticket_ref')->nullable();
            $table->text('override_notes')->nullable();
            $table->dateTime('sla_due_at')->nullable();
            $table->dateTime('sla_paused_at')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
