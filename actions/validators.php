<?php

$validators = [
    'url' => function($value) {
        $pattern = '/^http(s)?:\/\/.*$/';
        if (preg_match($pattern, $value)) {
            return $value;
        }
        throw new Exception("EbscoWidget: L'url est invalide.");
    },
    'password' => function ($value) {
        $pattern = '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/';
        if (preg_match($pattern, $value)) {
            return $value;
        }

        throw new Exception('EbscoWidget: Le secret doit avoir au moins 8 characters avec au moins une capitale, un chiffre et un charactére spécial.');
    }
];
