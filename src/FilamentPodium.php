<?php

namespace Maximemolivier\FilamentPodium;

use Maximemolivier\FilamentPodium\Widgets\PodiumWidget;

class FilamentPodium
{
    /**
     * Créer une nouvelle instance du widget Podium
     */
    public function widget(): PodiumWidget
    {
        return app(PodiumWidget::class);
    }
}
