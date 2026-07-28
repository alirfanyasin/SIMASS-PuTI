<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('tanggal', 50);
            $table->string('jam_masuk', 50);
            $table->string('jam_pulang', 50)->nullable();
            $table->text('pekerjaan')->nullable();
            $table->string('hari', 50)->nullable();
            $table->string('total_jam', 100)->nullable();
            $table->longText('foto')->nullable();
            $table->integer('menit_tambahan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
