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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date'); // Tanggal absen
            
            // 1. Absen Masuk
            $table->time('clock_in_time')->nullable();
            $table->string('status')->default('hadir'); // Hadir, Izin, Sakit
            $table->string('reason')->nullable(); // Alasan jika status != hadir

            // 2. Absen Keluar
            $table->time('clock_out_time')->nullable();

            // 3. Absen Lembur
            $table->time('overtime_start')->nullable();
            $table->time('overtime_end')->nullable();
            $table->text('overtime_reason')->nullable();

            $table->timestamps();
            
            // Mencegah user absen 2x di tanggal yang sama (Constraint)
            $table->unique(['user_id', 'date']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
