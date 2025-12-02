<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            {{ $customTitle ?? __('filament-podium::podium.title') }}
        </x-slot>

        @if ($items->isEmpty())
            <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                {{ __('filament-podium::podium.no_data') }}
            </div>
        @elseif($showExtendedLayout)
            {{-- Extended layout: Podium (left) + List (right) --}}
            <div class="flex flex-row" style="max-height: 280px; overflow-y: hidden;">
                {{-- Podium Section (Left) --}}
                <div class="w-1/2 pr-4">
                    <div class="flex justify-center items-end space-x-4 min-h-[12rem] my-4">
                        @php
                            $heights = [180, 120, 70];
                            $widths = [80, 70, 70];
                            $order = [1, 0, 2]; // Display order: 2nd, 1st, 3rd
                            $delays = [200, 100, 300];
                        @endphp

                        @foreach ($order as $position)
                            @if (isset($items[$position]))
                                @php $item = $items[$position]; @endphp
                                <div class="flex flex-col items-center transition-all duration-500 hover:translate-y-1"
                                    x-data="{}"
                                    x-init="setTimeout(() => { $el.style.opacity = 1; $el.style.transform = 'translateY(0)'; }, {{ $delays[$loop->index] }})"
                                    style="opacity: 0; transform: translateY(20px);">

                                    <div class="text-center mb-2">
                                        @if ($position === 0 && $showCrown)
                                            <div class="flex justify-center mb-1">
                                                <x-filament::icon
                                                    icon="heroicon-s-trophy"
                                                    class="w-6 h-6 text-yellow-500"
                                                />
                                            </div>
                                        @endif

                                        @if (!empty($item['avatar']))
                                            <div class="flex justify-center mb-1">
                                                <img src="{{ $item['avatar'] }}"
                                                     alt="{{ $item['label'] }}"
                                                     class="w-{{ $position === 0 ? 10 : 8 }} h-{{ $position === 0 ? 10 : 8 }} rounded-full object-cover border border-gray-200 dark:border-gray-700">
                                            </div>
                                        @endif

                                        <div class="{{ $position === 0 ? 'font-bold text-gray-800 dark:text-white' : 'font-medium text-gray-700 dark:text-gray-300' }} truncate max-w-[80px]">
                                            {{ $item['label'] }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item['value'] }}
                                        </div>
                                    </div>

                                    <div class="relative">
                                        <div style="width: {{ $widths[$position] }}px; height: {{ $heights[$position] }}px;"
                                             class="rounded-t-lg relative overflow-hidden shadow-sm border
                                                    {{ $position === 0 ? 'bg-primary-100 dark:bg-gray-700 border-primary-200 dark:border-gray-600' : 'bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700' }}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- List Section (Right) --}}
                <div class="w-1/2 pl-4">
                    <div class="border rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 overflow-y-auto h-full">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($items->slice(3) as $item)
                                <li class="p-3 transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 font-medium text-gray-500 dark:text-gray-400 w-6 text-center">
                                            {{ $item['position'] }}
                                        </div>

                                        @if (!empty($item['avatar']))
                                            <img src="{{ $item['avatar'] }}"
                                                 alt="{{ $item['label'] }}"
                                                 class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-gray-700 flex-shrink-0">
                                        @endif

                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $item['label'] }}
                                            </p>
                                        </div>

                                        <div class="flex-shrink-0 text-sm text-gray-500 dark:text-gray-300">
                                            {{ $item['value'] }}
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @else
            {{-- Standard layout: Just podium --}}
            <div class="flex justify-center items-end space-x-4 min-h-[12rem] my-4">
                @php
                    $heights = [180, 120, 70];
                    $widths = [80, 70, 70];
                    $order = [1, 0, 2]; // Display order: 2nd, 1st, 3rd
                    $delays = [200, 100, 300];
                @endphp

                @foreach ($order as $position)
                    @if (isset($items[$position]))
                        @php $item = $items[$position]; @endphp
                        <div class="flex flex-col items-center transition-all duration-500 hover:translate-y-1"
                            x-data="{}"
                            x-init="setTimeout(() => { $el.style.opacity = 1; $el.style.transform = 'translateY(0)'; }, {{ $delays[$loop->index] }})"
                            style="opacity: 0; transform: translateY(20px);">

                            <div class="text-center mb-2">
                                @if ($position === 0 && $showCrown)
                                    <div class="flex justify-center mb-1">
                                        <x-filament::icon
                                            icon="heroicon-s-trophy"
                                            class="w-6 h-6 text-yellow-500"
                                        />
                                    </div>
                                @endif

                                @if (!empty($item['avatar']))
                                    <div class="flex justify-center mb-1">
                                        <img src="{{ $item['avatar'] }}"
                                             alt="{{ $item['label'] }}"
                                             class="w-{{ $position === 0 ? 10 : 8 }} h-{{ $position === 0 ? 10 : 8 }} rounded-full object-cover border border-gray-200 dark:border-gray-700">
                                    </div>
                                @endif

                                <div class="{{ $position === 0 ? 'font-bold text-gray-800 dark:text-white' : 'font-medium text-gray-700 dark:text-gray-300' }} truncate max-w-[80px]">
                                    {{ $item['label'] }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item['value'] }}
                                </div>
                            </div>

                            <div class="relative">
                                <div style="width: {{ $widths[$position] }}px; height: {{ $heights[$position] }}px;"
                                     class="rounded-t-lg relative overflow-hidden shadow-sm border
                                            {{ $position === 0 ? 'bg-primary-100 dark:bg-gray-700 border-primary-200 dark:border-gray-600' : 'bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700' }}">
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
