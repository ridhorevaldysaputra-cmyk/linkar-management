<x-filament-widgets::widget>
    <x-filament::section class="h-full">
        
        {{-- Container Utama: Flex Row untuk mensejajarkan Icon dan Teks --}}
        <div class="flex items-center gap-5 h-full">
            
            {{-- BAGIAN 1: ICON BESAR --}}
            <div class="flex-shrink-0">
                @if($hasClockedIn)
                    <div class="p-3 bg-success-50 dark:bg-success-900/50 rounded-full">
                        <x-heroicon-m-check-circle class="w-8 h-8 text-success-600 dark:text-success-400" />
                    </div>
                @else
                    <div class="p-3 bg-warning-50 dark:bg-warning-900/50 rounded-full animate-pulse">
                        <x-heroicon-m-exclamation-circle class="w-8 h-8 text-warning-600 dark:text-warning-400" />
                    </div>
                @endif
            </div>

            {{-- BAGIAN 2: TEKS STATUS --}}
            <div class="flex-1">
                <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Status Absensi Hari Ini
                </h2>
                
                <p class="mt-1 text-2xl font-bold {{ $hasClockedIn ? 'text-success-600 dark:text-success-400' : 'text-warning-600 dark:text-warning-400' }}">
                    {{ $hasClockedIn ? 'Sudah Absen' : 'Belum Absen' }}
                </p>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $hasClockedIn ? 'Terima kasih, selamat bekerja!' : 'Mohon segera melakukan check-in.' }}
                </p>
            </div>

            {{-- BAGIAN 3: TOMBOL AKSI (Opsional, shortcut ke halaman absen) --}}
            <div>
                <a href="{{ url('/admin/attendances') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium tracking-wide text-white transition-colors duration-200 rounded-lg bg-primary-600 hover:bg-primary-500 focus:ring-2 focus:ring-offset-2 focus:ring-primary-600 focus:shadow-outline">
                    @if($hasClockedIn)
                        Lihat
                    @else
                        Absen
                    @endif
                </a>
            </div>

        </div>

    </x-filament::section>
</x-filament-widgets::widget>