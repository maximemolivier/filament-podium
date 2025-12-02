<?php

namespace Maximemolivier\FilamentPodium;

use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentPodiumServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-podium';

    public static string $viewNamespace = 'filament-podium';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews(static::$viewNamespace)
            ->hasTranslations()
            ->hasConfigFile();
    }

    public function packageBooted(): void
    {
        FilamentAsset::register(
            assets: [
                Css::make('filament-podium-styles', __DIR__ . '/../resources/dist/filament-podium.css'),
                Js::make('filament-podium-scripts', __DIR__ . '/../resources/dist/filament-podium.js'),
            ],
            package: 'maximemolivier/filament-podium'
        );
    }
}
