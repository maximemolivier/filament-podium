<?php

namespace Maximemolivier\FilamentPodium\Widgets;

use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;


class PodiumWidget extends Widget
{
    protected static string $view = 'filament-podium::widgets.podium-widget';

    protected $modelClass;
    protected $attribute;
    protected $direction = 'desc';
    protected $labelAttribute;
    protected $avatarAttribute = null;
    protected $limit = 3;

    protected $medals = [
        1 => 'gold',
        2 => 'silver',
        3 => 'bronze',
    ];

    public function mount(): void
    {
        if (method_exists($this, 'setUp')) {
            $this->setUp();
        }
    }

    public static function make(array $properties = []): WidgetConfiguration
    {
        return app(static::class)->configure($properties);
    }

    public function configure(array $properties = []): WidgetConfiguration
    {
        $configuration = new WidgetConfiguration($this);

        return $configuration;
    }

    public function model(string $modelClass): static
    {
        $this->modelClass = $modelClass;
        return $this;
    }

    public function attribute(string $attribute): static
    {
        $this->attribute = $attribute;
        return $this;
    }

    public function label(string $labelAttribute): static
    {
        $this->labelAttribute = $labelAttribute;
        return $this;
    }

    public function avatar(?string $avatarAttribute): static
    {
        $this->avatarAttribute = $avatarAttribute;
        return $this;
    }

    public function asc(): static
    {
        $this->direction = 'asc';
        return $this;
    }

    public function desc(): static
    {
        $this->direction = 'desc';
        return $this;
    }

    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    protected function getData(): Collection
    {
        if (empty($this->modelClass) || empty($this->attribute)) {
            \Log::warning('Podium widget: modelClass or attribute not set', [
                'modelClass' => $this->modelClass,
                'attribute' => $this->attribute,
            ]);
            return collect();
        }

        try {
            /** @var Builder $query */
            $query = app($this->modelClass)->query();

            $items = $query
                ->orderBy($this->attribute, $this->direction)
                ->limit($this->limit)
                ->get();

            return $items->map(function (Model $model, int $index) {
                $position = $index + 1;
                return [
                    'model' => $model,
                    'position' => $position,
                    'value' => $model->{$this->attribute},
                    'label' => $model->{$this->labelAttribute ?? 'name'},
                    'avatar' => $this->avatarAttribute ? $model->{$this->avatarAttribute} : null,
                    'medal' => $this->medals[$position] ?? null,
                ];
            });
        } catch (\Exception $e) {
            \Log::error('Podium widget error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            return collect();
        }
    }

    protected function getViewData(): array
    {
        return [
            'items' => $this->getData(),
            'direction' => $this->direction,
            'attribute' => $this->attribute,
        ];
    }
}
