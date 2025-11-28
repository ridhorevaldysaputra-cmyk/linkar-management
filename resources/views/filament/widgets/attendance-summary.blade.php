<x-filament-widgets::widget>
    <x-filament::section class="h-full">
        
        {{-- HEADER: Judul & Badge Bulan --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-sm font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                Statistik {{ $bulan }}
            </h2>
            <div class="flex items-center gap-1 px-2 py-1 rounded-md bg-primary-50 text-primary-700 dark:bg-primary-900/50 dark:text-primary-400">
                <x-heroicon-m-calendar class="w-4 h-4" />
                <span class="text-xs font-semibold">Bulan Ini</span>
            </div>
        </div>

        {{-- CONTENT: Flex Row (Horizontal) --}}
        <div class="flex flex-row items-center justify-between divide-x divide-gray-200 dark:divide-gray-700">
            
            {{-- ITEM 1: JAM KERJA --}}
            <div class="flex-1 px-2 text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Total Jam</p>
                <p class="text-2xl font-bold text-primary-600 dark:text-primary-400 leading-none">
                    {{ $jamKerja }}
                </p>
            </div>

            {{-- ITEM 2: HADIR --}}
            <div class="flex-1 px-2 text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Hadir</p>
                <p class="text-2xl font-bold text-success-600 dark:text-success-400 leading-none">
                    {{ $totalHadir }}
                </p>
            </div>

            {{-- ITEM 3: IZIN/SAKIT --}}
            <div class="flex-1 px-2 text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Izin / Sakit</p>
                <p class="text-2xl font-bold text-danger-600 dark:text-danger-400 leading-none">
                    {{ $totalTidakHadir }}
                </p>
            </div>

        </div>

    </x-filament::section>
</x-filament-widgets::widget>