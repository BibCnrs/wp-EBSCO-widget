<?php

require_once 'validators.php';

/**
 * Validate the settings saved.
 *
 * @param array $input
 * @return array
 */
$getValidateSettings = function ($config) use ($validators) {
    return function($input) use ($config, $validators)
    {
        foreach ($input AS $key => $value) {
            if (!isset($value)) {
                unset($input[$key]);
                continue;
            }

            if (isset($config->settings[$key]['validator'])) {
                try {
                    $input[$key] = $validators[$config->settings[$key]['validator']]($value);
                } catch (Exception $error) {
                    add_settings_error(
                        $config->tag,
                        $config->tag,
                        $error->getMessage(),
                        'error'
                    );
                    unset($input[$key]);
                }
            }
        }

        return $input;
    };
};
