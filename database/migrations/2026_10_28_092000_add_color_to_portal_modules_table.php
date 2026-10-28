<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('portal_modules', function (Blueprint $table) {
            $table->string('color', 30)->default('red')->after('icon');
        });

        $colorMap = [
            'admin' => 'dark',
            'presensi' => 'red',
            'eticket' => 'emerald',
            'neing' => 'blue',
            'webosistant' => 'amber',
            'inventory' => 'purple',
        ];

        foreach ($colorMap as $code => $color) {
            DB::table('portal_modules')
                ->where('code', $code)
                ->update(['color' => $color]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portal_modules', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};
