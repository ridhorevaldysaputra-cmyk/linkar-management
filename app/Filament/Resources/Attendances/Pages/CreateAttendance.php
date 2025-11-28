<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
use App\Models\Attendance;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class CreateAttendance extends CreateRecord
{
    protected static string $resource = AttendanceResource::class;

    // Fungsi ini berjalan otomatis SEBELUM data disimpan
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Paksa user_id dan date agar sesuai dengan yang login & hari ini
        $data['user_id'] = auth()->id();
        $data['date'] = now()->format('Y-m-d');

        // 2. Cek apakah sudah ada absen untuk user ini di tanggal ini?
        $existingAttendance = Attendance::where('user_id', $data['user_id'])
            ->where('date', $data['date'])
            ->first();

        if ($existingAttendance) {
            // Jika sudah ada, kirim Notifikasi Error
            Notification::make()
                ->title('Gagal Absen')
                ->body('Anda sudah melakukan absen masuk hari ini.')
                ->danger()
                ->send();

            // Hentikan proses penyimpanan
            $this->halt();
        }

        return $data;
    }

    // (Opsional) Redirect kemana setelah sukses absen?
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}