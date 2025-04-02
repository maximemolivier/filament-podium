<?php

namespace Maximemolivier\FilamentPodium\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PodiumWidget extends Widget
{
    protected static string $view = 'filament-podium::widgets.podium-widget';

    protected string $modelClass;
    protected string $attribute;
    protected string $direction = 'desc';
    protected string $labelAttribute;
    protected ?string $avatarAttribute = null;
    protected int $limit = 3;

    protected array $medals = [
        1 => 'gold',
        2 => 'silver',
        3 => 'bronze',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public static function make(): static
    {
        return app(static::class);
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
        if (!isset($this->modelClass) || !isset($this->attribute)) {
            return collect();
        }

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
