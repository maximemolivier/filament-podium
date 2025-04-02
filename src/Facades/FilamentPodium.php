<?php

namespace Maximemolivier\FilamentPodium\Facades;

use Illuminate\Support\Facades\Facade;
use Maximemolivier\FilamentPodium\Widgets\PodiumWidget;

/**
 * @method static PodiumWidget widget()
 * @see \VotreNom\FilamentPodium\FilamentPodium
 */
class FilamentPodium extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Maximemolivier\FilamentPodium\FilamentPodium::class;
    }

    public static function widget(): PodiumWidget
    {
        return app(PodiumWidget::class);
    }
}
