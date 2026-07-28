<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('overtime_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('overtime_id')->constrained('overtimes')->cascadeOnDelete();
            $table->foreignId('presence_id')->constrained('presences')->cascadeOnDelete();
            $table->string('tanggal_transfer', 50);
            $table->integer('durasi_menit');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('overtime_transfers');
    }
};
