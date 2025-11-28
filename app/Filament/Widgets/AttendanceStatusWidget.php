<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\Widget;

class AttendanceStatusWidget extends Widget
{
    // Hubungkan ke view baru yang akan kita buat
    protected string $view = 'filament.widgets.attendance-status-widget';

    protected static ?int $sort = -10;

    // Gunakan logic responsif yang sama dengan widget sebelah agar 50:50
    protected int | string | array $columnSpan = [
        'md' => 2, 
        'xl' => 1,
    ];

    protected function getViewData(): array
    {
        $hasClockedIn = false;

        if (auth()->check()) {
            $hasClockedIn = Attendance::where('user_id', auth()->id())
                ->where('date', now()->format('Y-m-d'))
                ->exists();
        }

        return [
            'hasClockedIn' => $hasClockedIn,
        ];
    }
}