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
            'type' => 'password',
            'placeholder' => ''
        ]
    ]
];
