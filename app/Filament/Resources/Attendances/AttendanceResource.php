<?php

namespace App\Filament\Resources\Attendances;

use BackedEnum;
use Filament\Forms;
use Filament\Tables;
use App\Models\Attendance;
use Filament\Tables\Table;
use Filament\Schemas\Schema; 
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\Attendances\Pages;
use Filament\Forms\Components\TextInput; // Tambahkan ini
use Illuminate\Database\Eloquent\Builder; // Tambahkan ini
use Filament\Tables\Enums\FiltersLayout; // Pastikan import ini ada

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Absensi';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // INFO KARYAWAN
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Nama Karyawan')
                    ->default(fn () => auth()->id())
                    ->disabled()
                    ->dehydrated(),
                
                Forms\Components\DatePicker::make('date')
                    ->default(now())
                    ->disabled()
                    ->dehydrated()
                    ->label('Tanggal'),

                // ABSEN MASUK (Muncul saat Create)
                Forms\Components\TimePicker::make('clock_in_time')
                    ->default(now())
                    ->dehydrated()
                    ->label('Jam Check-In')
                    ->required()
                    ->visible(fn ($livewire) => $livewire instanceof Pages\CreateAttendance),
                
                Forms\Components\Select::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'sakit' => 'Sakit',
                    ])
                    ->default('hadir')
                    ->required()
                    ->reactive()
                    ->visible(fn ($livewire) => $livewire instanceof Pages\CreateAttendance),

                Forms\Components\Textarea::make('reason')
                    ->label('Alasan / Keterangan')
                    ->hidden(fn ($get) => $get('status') === 'hadir')
                    ->visible(fn ($livewire) => $livewire instanceof Pages\CreateAttendance),

                // ABSEN KELUAR & LEMBUR (Muncul saat Edit)
                Forms\Components\TimePicker::make('clock_out_time')
                    ->label('Jam Check-Out')
                    ->default(now())
                    ->visible(fn ($livewire) => $livewire instanceof Pages\EditAttendance),
                
                Forms\Components\TimePicker::make('overtime_start')
                    ->label('Mulai Lembur')
                    ->visible(fn ($livewire) => $livewire instanceof Pages\EditAttendance),

                Forms\Components\TimePicker::make('overtime_end')
                    ->label('Selesai Lembur')
                    ->visible(fn ($livewire) => $livewire instanceof Pages\EditAttendance),
                    
                Forms\Components\Textarea::make('overtime_reason')
                    ->label('Kegiatan Lembur')
                    ->visible(fn ($livewire) => $livewire instanceof Pages\EditAttendance),
            ]);
    }

public static function table(Table $table): Table
{
    return $table
        // --- KONFIGURASI UTAMA ---
        ->filtersLayout(FiltersLayout::AboveContent) // Filter di atas tabel
        ->deferFilters(false) // PENTING: Mematikan tombol "Apply", jadi filter langsung jalan (Live)
        ->hiddenFilterIndicators()
        ->filtersFormColumns([ // Mengatur Grid: 4 kolom (Tahun, Bulan, Search, Kosong)
            'md' => 3, 
            'lg' => 3, 
        ])
        
        // --- HILANGKAN BACKGROUND KOTAK FILTER ---
        // Kita inject CSS langsung ke komponen tabel agar kotaknya hilang
        ->extraAttributes([
            'class' => 'attendance-table-clean-filter',
        ])
        
        // --- SORTING & STYLE BARIS ---
        ->defaultSort('date', 'desc')
        ->recordClasses(fn (Attendance $record) => 
            $record->date < now()->format('Y-m-d') ? 'opacity-50 bg-gray-50 dark:bg-gray-800' : null
        )
        
        // --- KOLOM (HAPUS searchable() DARI SINI) ---
        // Kita matikan fitur search bawaan agar tidak ada 2 kolom search
        ->columns([
            Tables\Columns\TextColumn::make('user.name')->label('Nama'), 
            Tables\Columns\TextColumn::make('date')->date('d M Y')->label('Tanggal'),
            Tables\Columns\TextColumn::make('clock_in_time')->time('H:i')->label('Masuk'),
            Tables\Columns\TextColumn::make('clock_out_time')->time('H:i')->label('Keluar')->placeholder('-'),
            Tables\Columns\BadgeColumn::make('status')
                ->colors(['success' => 'hadir', 'warning' => 'izin', 'danger' => 'sakit']),
        ])
        
        // --- FILTER GABUNGAN (DROPDOWN + SEARCH) ---
        ->filters([
            // 1. FILTER TAHUN
            SelectFilter::make('year')
                ->label(' ') // Kosongkan label
                ->options([
                    now()->year => now()->year,
                    now()->subYear()->year => now()->subYear()->year,
                    now()->subYears(2)->year => now()->subYears(2)->year,
                ])
                ->default(now()->year)
                ->query(fn ($query, $state) => $query->whereYear('date', $state['value']))
                ->selectablePlaceholder(false)
                ->native(false),

            // 2. FILTER BULAN
            SelectFilter::make('month')
                ->label(' ') // Kosongkan label
                ->options([
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                    '04' => 'April',   '05' => 'Mei',      '06' => 'Juni',
                    '07' => 'Juli',    '08' => 'Agustus',  '09' => 'September',
                    '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                ])
                ->default(now()->format('m'))
                ->query(fn ($query, $state) => $query->whereMonth('date', $state['value']))
                ->selectablePlaceholder(false)
                ->native(false),
        ])
        ->actions([
            EditAction::make()
                ->label('Pulang / Lembur')
                ->icon('heroicon-m-pencil-square')
                ->visible(fn (Attendance $record) => 
                    auth()->user()->hasRole('super_admin') || $record->date >= now()->format('Y-m-d')
                ),
            DeleteAction::make()
                ->visible(fn () => auth()->user()->hasRole('super_admin')),
        ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}