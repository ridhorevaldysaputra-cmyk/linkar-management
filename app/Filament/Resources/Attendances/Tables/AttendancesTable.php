<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Tables\Table;
use Filament\Tables;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->label('Tanggal')
                    ->sortable(),
                Tables\Columns\TextColumn::make('clock_in_time')
                    ->time()
                    ->label('Masuk'),
                Tables\Columns\TextColumn::make('clock_out_time')
                    ->time()
                    ->label('Keluar'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'hadir',
                        'warning' => 'izin',
                        'danger' => 'sakit',
                    ]),
            ])
            ->filters([
                // Filter tanggal bisa ditambahkan disini nanti
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Absen Keluar / Lembur'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}