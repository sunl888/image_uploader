<?php

return [
    'base_url' => 'img',
    'route_name' => 'image',

    'path' => 'uploads/images',
    'cache_path' => 'uploads/images/.cache',

    'disk' => 'local',
    'cache_disk' => 'local',

    'upload_key' => 'image',

    'table' => 'image_uploads',

    'allow_types' => [
        'jpg', 'png'
    ],

    'default_style' => [
        'q' => 90,
        'fit' => 'crop'
    ],
    // xs < sm < md < lg
    'presets' => [
        'avatar_xs' => [
            'w' => 30,
            'h' => 30,
            'fit' => 'crop',
        ],
        'avatar_sm' => [
            'w' => 100,
            'h' => 100,
            'fit' => 'crop',
        ],
        'avatar_md' => [
            'w' => 160,
            'h' => 160,
            'fit' => 'crop',
        ],
        'case_cover' => [
            'w' => 255,
            'h' => 180,
            'fit' => 'crop',
        ],
        'list_news_cover' => [
            'w' => 350,
            'h' => 230,
            'fit' => 'crop',
        ],
    ],
];