<?php

$config = (object)[
    'tag' => 'ebsco_widget',
    'name' => 'EBBSCO Widget',
    'version' => '0.1',
    'settings' => [
        'url' => [
            'description' => 'Url pour accéder à BibCnrs Api.',
            'validator' => 'url',
            'type' => 'url',
            'placeholder' => 'http://BibCnrsHost'
        ],
        'secret' => [
            'description' => 'Secret to encode jwt token used by the widget to access the api.',
            'validator' => 'password',
            'type' => 'password',
        ]
    ],
    'home' => plugin_dir_path(__FILE__),
    'url' => plugin_dir_url(__FILE__),
    'views' => plugin_dir_path(__FILE__). 'views' . DIRECTORY_SEPARATOR,
    'actions' => plugin_dir_path(__FILE__). 'actions' . DIRECTORY_SEPARATOR
];
