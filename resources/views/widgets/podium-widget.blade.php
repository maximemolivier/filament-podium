<x-filament-widgets::widget>
    <x-filament::section>
        <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
            {{ __('filament-podium::podium.title') }}
        </h3>

        @if ($items->isEmpty())
            <div class="p-4 text-center text-gray-500">
                Aucune donnée disponible pour le podium
            </div>
        @else
            <div class="flex justify-center items-end space-x-6 min-h-[12rem] my-8 gap-2">
                @php
                    $height1 = 100;
                    $height2 = 75;
                    $height3 = 50;
                @endphp

                <!-- Second place podium -->
                <div class="flex flex-col items-center transition-all duration-500 hover:translate-y-1"
                    x-data="{}" x-init="setTimeout(() => {
                        $el.style.opacity = 1;
                        $el.style.transform = 'translateY(0)';
                    }, 200)" style="opacity: 0; transform: translateY(20px);">

                    @if (isset($items[1]))
                        <div class="text-center mb-2">
                            <div class="font-medium text-gray-700 dark:text-gray-300">{{ $items[1]['label'] }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $items[1]['value'] }}</div>
                        </div>
                    @endif

                    <!-- Podium block with Filament style -->
                    <div class="relative">
                        <div style="width: 70px; height: {{ $height2 }}px;"
                            class="rounded-t-lg relative overflow-hidden bg-gray-100 dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">

                        </div>
                    </div>
                </div>

                <!-- First place podium (taller) -->
                <div class="flex flex-col items-center transition-all duration-500 hover:translate-y-1"
                    x-data="{}" x-init="setTimeout(() => {
                        $el.style.opacity = 1;
                        $el.style.transform = 'translateY(0)';
                    }, 100)" style="opacity: 0; transform: translateY(20px);">

                    @if (isset($items[0]))
                        <div class="text-center mb-2">
                            <div class="font-bold text-gray-800 dark:text-white">{{ $items[0]['label'] }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $items[0]['value'] }}</div>
                        </div>
                    @endif

                    <div class="relative">
                        <div style="width: 80px; height: {{ $height1 }}px;"
                            class="rounded-t-lg relative overflow-hidden bg-primary-100 dark:bg-primary-800 shadow-sm border border-primary-200 dark:border-primary-700">

                        </div>
                    </div>
                </div>

                <!-- Third place podium (shortest) -->
                <div class="flex flex-col items-center transition-all duration-500 hover:translate-y-1"
                    x-data="{}" x-init="setTimeout(() => {
                        $el.style.opacity = 1;
                        $el.style.transform = 'translateY(0)';
                    }, 300)" style="opacity: 0; transform: translateY(20px);">

                    @if (isset($items[2]))
                        <div class="text-center mb-2">
                            <div class="font-medium text-gray-700 dark:text-gray-300">{{ $items[2]['label'] }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $items[2]['value'] }}</div>
                        </div>
                    @endif

                    <div class="relative">
                        <div style="width: 70px; height: {{ $height3 }}px;"
                            class="rounded-t-lg relative overflow-hidden bg-gray-100 dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700">

                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Ranking info with Filament styling -->
        <div class="text-xs text-center pt-2 text-gray-500 dark:text-gray-400">
            {{ __('filament-podium::podium.ranked_by') }}: {{ $attribute ?? 'N/A' }}
            ({{ isset($direction) && $direction === 'desc' ? __('filament-podium::podium.highest') : __('filament-podium::podium.lowest') }})
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
