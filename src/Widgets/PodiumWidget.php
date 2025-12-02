<?php

namespace Maximemolivier\FilamentPodium\Widgets;

use Closure;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PodiumWidget extends Widget
{
    protected int | string | array $columnSpan = 'full';

    protected static string $view = 'filament-podium::widgets.podium-widget';

    protected array $medals = [
        1 => 'gold',
        2 => 'silver',
        3 => 'bronze',
    ];

    protected ?string $modelClass = null;
    protected ?string $attribute = null;
    protected string $sortDirection = 'desc';
    protected ?string $labelAttribute = null;
    protected ?string $valueAttribute = null;
    protected ?string $avatarAttribute = null;
    protected int $limit = 3;
    protected bool $showCrown = false;
    protected ?string $customTitle = null;
    protected ?Closure $titleCallback = null;
    protected ?Closure $formatValueCallback = null;
    protected ?Closure $labelCallback = null;
    protected ?Closure $queryCallback = null;
    protected ?array $rawSql = null;

    public function mount(): void
    {
        if (method_exists($this, 'setUp')) {
            $this->setUp();
        }
    }

    /**
     * Set the Eloquent model class to query.
     */
    public function model(string $modelClass): static
    {
        $this->modelClass = $modelClass;

        return $this;
    }

    /**
     * Set the attribute to sort/rank by.
     */
    public function attribute(string $attribute): static
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * Set the attribute to use for labels.
     */
    public function label(string $labelAttribute): static
    {
        $this->labelAttribute = $labelAttribute;

        return $this;
    }

    /**
     * Set a custom callback to generate labels.
     */
    public function labelUsing(Closure $callback): static
    {
        $this->labelCallback = $callback;

        return $this;
    }

    /**
     * Set the attribute to use for displaying the value (if different from sort attribute).
     */
    public function valueAttribute(string $valueAttribute): static
    {
        $this->valueAttribute = $valueAttribute;

        return $this;
    }

    /**
     * Set a callback to format the displayed value.
     */
    public function formatValue(Closure $callback): static
    {
        $this->formatValueCallback = $callback;

        return $this;
    }

    /**
     * Set the attribute to use for avatars.
     */
    public function avatar(?string $avatarAttribute): static
    {
        $this->avatarAttribute = $avatarAttribute;

        return $this;
    }

    /**
     * Show a crown/trophy icon above the first place.
     */
    public function crown(bool $show = true): static
    {
        $this->showCrown = $show;

        return $this;
    }

    /**
     * Sort in ascending order (lowest first).
     */
    public function asc(): static
    {
        $this->sortDirection = 'asc';

        return $this;
    }

    /**
     * Sort in descending order (highest first). This is the default.
     */
    public function desc(): static
    {
        $this->sortDirection = 'desc';

        return $this;
    }

    /**
     * Set the maximum number of items to display.
     */
    public function limit(int $limit): static
    {
        $this->limit = max(1, $limit);

        return $this;
    }

    /**
     * Set a custom title for the widget.
     *
     * @param  string|Closure  $title  A static title or a callback that receives the items collection
     */
    public function title(string | Closure $title): static
    {
        if ($title instanceof Closure) {
            $this->titleCallback = $title;
        } else {
            $this->customTitle = $title;
        }

        return $this;
    }

    /**
     * Set a raw SQL query to fetch data.
     *
     * The query must return results with at least 'label' and 'value' columns.
     * Optionally include 'avatar' column for avatars.
     */
    public function rawQuery(string $sql, array $bindings = []): static
    {
        $this->rawSql = [
            'sql' => $sql,
            'bindings' => $bindings,
        ];

        return $this;
    }

    /**
     * Set a custom query callback.
     *
     * The callback receives a query builder and should return it with any
     * modifications. The query should include ordering and will have limit applied.
     */
    public function query(Closure $callback): static
    {
        $this->queryCallback = $callback;

        return $this;
    }

    /**
     * Get the data for the podium.
     */
    protected function getData(): Collection
    {
        if ($this->rawSql) {
            return $this->getRawData();
        }

        if ($this->queryCallback) {
            return $this->getDataFromCallback();
        }

        if (empty($this->modelClass) || empty($this->attribute)) {
            return collect();
        }

        $items = app($this->modelClass)::query()
            ->orderBy($this->attribute, $this->sortDirection)
            ->limit($this->limit)
            ->get();

        return $this->formatItems($items);
    }

    /**
     * Get data from a raw SQL query.
     */
    protected function getRawData(): Collection
    {
        $results = DB::select($this->rawSql['sql'], $this->rawSql['bindings']);

        return collect($results)->map(function ($item, $index) {
            $position = $index + 1;
            $value = $item->value ?? null;

            if ($this->formatValueCallback && $value !== null) {
                $value = ($this->formatValueCallback)($value, $item);
            }

            $label = $item->label ?? null;
            if ($this->labelCallback) {
                $label = ($this->labelCallback)($item);
            }

            return [
                'model' => $item,
                'position' => $position,
                'value' => $value,
                'label' => $label,
                'avatar' => $item->avatar ?? null,
                'medal' => $this->medals[$position] ?? null,
            ];
        });
    }

    /**
     * Get data from a custom query callback.
     */
    protected function getDataFromCallback(): Collection
    {
        $query = $this->modelClass
            ? app($this->modelClass)::query()
            : DB::query();

        $customQuery = ($this->queryCallback)($query);
        $results = $customQuery->limit($this->limit)->get();

        return $this->formatItems($results);
    }

    /**
     * Format items for display.
     */
    protected function formatItems($items): Collection
    {
        return collect($items)->map(function ($item, $index) {
            $position = $index + 1;

            // Determine the value
            $value = $this->valueAttribute
                ? ($item->{$this->valueAttribute} ?? $item->value ?? null)
                : ($item->{$this->attribute} ?? $item->value ?? null);

            if ($this->formatValueCallback && $value !== null) {
                $value = ($this->formatValueCallback)($value, $item);
            }

            // Determine the label
            if ($this->labelCallback) {
                $label = ($this->labelCallback)($item);
            } else {
                $labelField = $this->labelAttribute ?? 'label';
                $label = $item->{$labelField} ?? $item->label ?? $item->name ?? "#{$position}";
            }

            // Determine the avatar
            $avatar = null;
            if ($this->avatarAttribute) {
                $avatar = $item->{$this->avatarAttribute} ?? null;
            } elseif (isset($item->avatar)) {
                $avatar = $item->avatar;
            }

            return [
                'model' => $item,
                'position' => $position,
                'value' => $value,
                'label' => $label,
                'avatar' => $avatar,
                'medal' => $this->medals[$position] ?? null,
            ];
        });
    }

    /**
     * Determine if the extended layout should be shown (podium + list).
     */
    protected function shouldShowExtendedLayout(): bool
    {
        return $this->limit > 3;
    }

    /**
     * Get the data for the view.
     */
    protected function getViewData(): array
    {
        $items = $this->getData();

        $title = $this->customTitle ?? __('filament-podium::podium.title');
        if ($this->titleCallback) {
            $title = ($this->titleCallback)($items);
        }

        return [
            'items' => $items,
            'showExtendedLayout' => $this->shouldShowExtendedLayout(),
            'showCrown' => $this->showCrown,
            'customTitle' => $title,
        ];
    }
}
