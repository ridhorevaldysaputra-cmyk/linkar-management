<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Console\Command;

class MarkAbsentUsers extends Command
{
    // Nama perintah untuk dipanggil
    protected $signature = 'attendance:mark-alpha';

    // Deskripsi
    protected $description = 'Menandai Alpha bagi karyawan yang tidak absen hari ini';

    public function handle()
    {
        // 1. Cek apakah hari ini Sabtu/Minggu? (Opsional, jika libur tidak dihitung alpha)
        if (now()->isWeekend()) {
            $this->info('Hari ini libur (Weekend), tidak ada proses Alpha.');
            return;
        }

        $today = now()->format('Y-m-d');
        $this->info("Memulai pengecekan absensi untuk tanggal: $today");

        // 2. Cari semua User yang BELUM punya data absen di tanggal hari ini
        // Kita gunakan 'whereDoesntHave' yang memanfaatkan relasi 'attendances' yang baru kita buat
        $users = User::whereDoesntHave('attendances', function ($query) use ($today) {
            $query->where('date', $today);
        })
        // Opsional: Filter hanya user yang bukan super_admin jika perlu
        // ->whereDoesntHave('roles', fn($q) => $q->where('name', 'super_admin'))
        ->get();

        if ($users->isEmpty()) {
            $this->info('Semua karyawan sudah absen. Bagus!');
            return;
        }

        // 3. Buat data Alpha untuk user yang bolos
        $count = 0;
        foreach ($users as $user) {
            Attendance::create([
                'user_id'       => $user->id,
                'date'          => $today,
                'status'        => 'alpha', // Status Alpha
                'reason'        => 'Tidak melakukan absensi (System Auto)',
                'clock_in_time' => null,
                'clock_out_time'=> null,
            ]);
            
            $this->info("User {$user->name} ditandai Alpha.");
            $count++;
        }

        $this->info("Selesai! Total $count karyawan ditandai Alpha.");
    }
}