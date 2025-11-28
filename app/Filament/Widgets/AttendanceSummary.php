<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\Widget;
use Carbon\Carbon;

class AttendanceSummary extends Widget
{
    protected string $view = 'filament.widgets.attendance-summary';
    
    protected static ?int $sort = -9;

    // --- IMPLEMENTASI LOGIKA RESPONSIF ---
    protected int|string|array $columnSpan = [
        'default' => 1,
        'sm' => 1,
        'md' => 1,
        'lg' => 1,
        'xl' => 1,
        '2xl' => 1,
        '3xl' => 1,
    ];

    protected function getViewData(): array
    {
        $userId = auth()->id();
        
        // Ambil data bulan ini
        $attendances = Attendance::where('user_id', $userId)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->get();

        $totalHadir = $attendances->where('status', 'hadir')->count();
        $totalTidakHadir = $attendances->whereIn('status', ['izin', 'sakit'])->count();

        $totalSeconds = 0;
        foreach ($attendances as $att) {
            if ($att->clock_in_time && $att->clock_out_time) {
                $start = Carbon::parse($att->clock_in_time);
                $end = Carbon::parse($att->clock_out_time);
                // Fix bug minus dengan abs()
                $totalSeconds += abs($end->diffInSeconds($start));
            }
        }

        $totalHours = floor($totalSeconds / 3600);
        $totalMinutes = floor(($totalSeconds % 3600) / 60);

        return [
            'totalHadir' => $attendances->where('status', 'hadir')->count(),
            'totalTidakHadir' => $attendances->whereIn('status', ['izin', 'sakit'])->count(),
            'jamKerja' => "0j 0m", // Ganti dengan variabel hasil hitunganmu
            'bulan' => now()->translatedFormat('F Y'),
        ];
    }
}