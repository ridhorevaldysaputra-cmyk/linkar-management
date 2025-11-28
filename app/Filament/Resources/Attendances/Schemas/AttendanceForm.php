<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use App\Filament\Resources\Attendances\Pages;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // --- BAGIAN 1: INFO KARYAWAN (Otomatis) ---
                Forms\Components\Section::make('Informasi Karyawan')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Nama Karyawan')
                            ->default(auth()->id())
                            ->disabled()
                            ->dehydrated(),
                        
                        Forms\Components\DatePicker::make('date')
                            ->default(now())
                            ->disabled()
                            ->dehydrated()
                            ->label('Tanggal'),
                    ])->columns(2),

                // --- BAGIAN 2: ABSEN MASUK ---
                Forms\Components\Section::make('Absen Masuk')
                    ->schema([
                        Forms\Components\TimePicker::make('clock_in_time')
                            ->default(now())
                            ->disabled()
                            ->dehydrated()
                            ->label('Jam Masuk')
                            ->required(),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'hadir' => 'Hadir',
                                'izin' => 'Izin',
                                'sakit' => 'Sakit',
                            ])
                            ->default('hadir')
                            ->required()
                            ->reactive(), 

                        Forms\Components\Textarea::make('reason')
                            ->label('Alasan / Keterangan')
                            ->hidden(fn ($get) => $get('status') === 'hadir'),
                    ])
                    // Hanya muncul saat Create
                    ->visible(fn ($livewire) => $livewire instanceof Pages\CreateAttendance),

                // --- BAGIAN 3: ABSEN KELUAR & LEMBUR ---
                Forms\Components\Section::make('Absen Keluar & Lembur')
                    ->schema([
                        Forms\Components\TimePicker::make('clock_out_time')
                            ->label('Jam Keluar')
                            ->default(now())
                            ->helperText('Klik simpan untuk absen keluar.'),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TimePicker::make('overtime_start')
                                    ->label('Mulai Lembur'),
                                Forms\Components\TimePicker::make('overtime_end')
                                    ->label('Selesai Lembur'),
                            ]),
                            
                        Forms\Components\Textarea::make('overtime_reason')
                            ->label('Kegiatan Lembur'),
                    ])
                    // Hanya muncul saat Edit
                    ->visible(fn ($livewire) => $livewire instanceof Pages\EditAttendance),
            ]);
    }
}