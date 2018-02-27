<?php

$validators = [
    'url' => function($value) {
        $pattern = '/^http(s)?:\/\/.*$/';
        if (preg_match($pattern, $value)) {
            return $value;
        }
        throw new Exception("EbscoWidget: L'url est invalide.");
    },
    'publication_sort' => function($value) {
        return true;
    }
];
