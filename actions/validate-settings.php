<?php

require EBSCO_WIDGET__PATH . 'actions' . DIRECTORY_SEPARATOR . 'validators.php';

/**
 * Validate the settings saved.
 *
 * @param array $input
 * @return array
 */
$validateSettings = function($input) use ($validators)
{
    require EBSCO_WIDGET__PATH . 'config.php';

    foreach ($input AS $key => $value) {
        if ($value == '') {
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
