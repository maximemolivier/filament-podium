<x-filament-widgets::widget>
    <x-filament::card>
        <div class="filament-podium">
            <h2 class="text-xl font-bold mb-4">
                {{ __('filament-podium::podium.title') }}
            </h2>

            <div class="podium-container flex justify-center items-end space-x-4 mb-6">
                @foreach ($items as $item)
                    @php
                        $height = match ($item['position']) {
                            1 => 'h-32',
                            2 => 'h-24',
                            3 => 'h-20',
                            default => 'h-16',
                        };

                        $medalColor = match ($item['medal'] ?? null) {
                            'gold' => 'bg-amber-400',
                            'silver' => 'bg-gray-300',
                            'bronze' => 'bg-amber-600',
                            default => 'bg-gray-200',
                        };
                    @endphp

                    <div
                        class="podium-item flex flex-col items-center animate-in slide-in-from-bottom-{{ 2 * $item['position'] }}">
                        @if ($item['avatar'])
                            <div class="avatar mb-2">
                                <img src="{{ $item['avatar'] }}" alt="{{ $item['label'] }}"
                                    class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-md">
                            </div>
                        @endif

                        <div class="text-center mb-2">
                            <div class="font-medium">{{ $item['label'] }}</div>
                            <div class="text-sm text-gray-500">{{ $item['value'] }}</div>
                        </div>

                        <div
                            class="relative podium-block w-20 {{ $height }} {{ $medalColor }} rounded-t-md shadow-md flex items-center justify-center">
                            <span
                                class="absolute -top-3 w-8 h-8 rounded-full bg-white shadow flex items-center justify-center font-bold text-gray-800">
                                {{ $item['position'] }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-xs text-gray-500 text-center">
                {{ __('filament-podium::podium.ranked_by') }}: {{ $attribute }}
                ({{ $direction === 'desc' ? __('filament-podium::podium.highest') : __('filament-podium::podium.lowest') }})
            </div>
        </div>
    </x-filament::card>
</x-filament-widgets::widget>

<style>
    @keyframes slideInFromBottom {
        0% {
            transform: translateY(50%);
            opacity: 0;
        }

        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .podium-container {
        min-height: 12rem;
    }

    .podium-item {
        animation: slideInFromBottom 0.5s ease-out forwards;
        animation-delay: calc(var(--animation-order) * 0.1s);
    }

    .podium-item:nth-child(1) {
        --animation-order: 2;
        order: 2;
    }

    .podium-item:nth-child(2) {
        --animation-order: 1;
        order: 1;
    }

    .podium-item:nth-child(3) {
        --animation-order: 3;
        order: 3;
    }
</style>
