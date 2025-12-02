<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    |
    | These are the default settings for the Podium widget. You can override
    | them when extending the PodiumWidget class using the fluent API.
    |
    */

    'defaults' => [
        // Maximum number of items to display
        'limit' => 3,

        // Show a crown/trophy icon on first place
        'show_crown' => false,

        // Sort direction: 'desc' (highest first) or 'asc' (lowest first)
        'sort_direction' => 'desc',
    ],
];
