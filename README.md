# Filament Podium Widget

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maximemolivier/filament-podium.svg?style=flat-square)](https://packagist.org/packages/maximemolivier/filament-podium)
[![Total Downloads](https://img.shields.io/packagist/dt/maximemolivier/filament-podium.svg?style=flat-square)](https://packagist.org/packages/maximemolivier/filament-podium)

A beautiful podium/leaderboard widget for Filament v4. Display rankings for anything: top sellers, best performers, highest scores, and more.

![Podium Widget Preview](https://raw.githubusercontent.com/maximemolivier/filament-podium/main/art/preview.png)

## Features

- **Visual Podium Display** - Beautiful 3-step podium for top 3 positions
- **Extended Layout** - Shows podium + scrollable list when displaying more than 3 items
- **Fully Customizable** - Labels, values, avatars, titles, and more
- **Dark Mode Support** - Seamless light/dark theme integration
- **Animations** - Smooth entrance animations
- **Fluent API** - Easy-to-use chainable configuration
- **Custom Queries** - Use Eloquent models, query builders, or raw SQL

## Requirements

- PHP 8.2+
- Laravel 11.x or 12.x
- Filament 4.x

## Installation

Install the package via Composer:

```bash
composer require maximemolivier/filament-podium
```

Optionally, publish the config file:

```bash
php artisan vendor:publish --tag="filament-podium-config"
```

## Usage

### Basic Usage

Create a new widget that extends `PodiumWidget`:

```php
namespace App\Filament\Widgets;

use App\Models\User;
use Maximemolivier\FilamentPodium\Widgets\PodiumWidget;

class TopSellersWidget extends PodiumWidget
{
    protected function setUp(): void
    {
        $this->model(User::class)
            ->attribute('sales_count')
            ->label('name')
            ->title('Top Sellers');
    }
}
```

Then register it in your Panel or Dashboard.

### With Avatars and Crown

```php
protected function setUp(): void
{
    $this->model(User::class)
        ->attribute('points')
        ->label('name')
        ->avatar('avatar_url')
        ->crown()
        ->limit(5)
        ->title('Leaderboard');
}
```

### Custom Value Formatting

```php
protected function setUp(): void
{
    $this->model(Product::class)
        ->attribute('revenue')
        ->label('name')
        ->formatValue(fn ($value) => '$' . number_format($value, 2))
        ->title('Top Products by Revenue');
}
```

### Custom Query

```php
protected function setUp(): void
{
    $this->model(User::class)
        ->query(fn ($query) => $query
            ->where('is_active', true)
            ->withCount('orders')
            ->orderByDesc('orders_count')
        )
        ->label('name')
        ->valueAttribute('orders_count')
        ->title('Most Active Users');
}
```

### Raw SQL Query

```php
protected function setUp(): void
{
    $this->rawQuery("
        SELECT
            users.name as label,
            COUNT(orders.id) as value,
            users.avatar as avatar
        FROM users
        JOIN orders ON orders.user_id = users.id
        WHERE orders.created_at >= ?
        GROUP BY users.id
        ORDER BY value DESC
        LIMIT 10
    ", [now()->subMonth()])
    ->title('Top Sellers This Month');
}
```

## Available Methods

| Method | Description |
|--------|-------------|
| `model(string $class)` | Set the Eloquent model to query |
| `attribute(string $attr)` | Set the attribute to rank/sort by |
| `label(string $attr)` | Set the attribute for display labels |
| `labelUsing(Closure $callback)` | Custom callback to generate labels |
| `valueAttribute(string $attr)` | Set attribute for displayed value (if different from sort) |
| `formatValue(Closure $callback)` | Format the displayed value |
| `avatar(string $attr)` | Set the attribute for avatar URLs |
| `crown(bool $show = true)` | Show trophy icon on first place |
| `asc()` | Sort ascending (lowest first) |
| `desc()` | Sort descending (highest first) - default |
| `limit(int $count)` | Maximum items to display |
| `title(string\|Closure $title)` | Set widget title (static or dynamic) |
| `query(Closure $callback)` | Custom query builder callback |
| `rawQuery(string $sql, array $bindings)` | Use raw SQL query |

## Configuration

The published config file (`config/filament-podium.php`):

```php
return [
    'defaults' => [
        'limit' => 3,
        'show_crown' => false,
        'sort_direction' => 'desc',
    ],
];
```

## Customization

### Publishing Views

To customize the widget's appearance:

```bash
php artisan vendor:publish --tag="filament-podium-views"
```

### Publishing Translations

```bash
php artisan vendor:publish --tag="filament-podium-translations"
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Olivier Maxime](https://github.com/maximemolivier)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
