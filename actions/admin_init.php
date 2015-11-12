<?php

/**
 * Append a settings field to the the fields section.
 *
 * @param array $args
 */
$settings_field = function ( array $options = array() )
{
    require EBSCO_WIDGET__PATH . 'config.php';
    $pluginOptions = get_option($config->tag);
    $atts = [
        'id' => sprintf('%s_%s' , $config->tag, $options['id']),
        'name' => sprintf('%s[%s]', $config->tag, $options['id']),
        'type' => (isset( $options['type'] ) ? $options['type'] : 'text'),
        'class' => 'small-text',
        'value' => (array_key_exists('default', $options) ? $options['default'] : null)
    ];
    if (isset( $pluginOptions[$options['id']])) {
        $atts['value'] = $pluginOptions[$options['id']];
    }
    if (isset( $options['placeholder'])) {
        $atts['placeholder'] = $options['placeholder'];
    }
    if (isset( $options['type'] ) && $options['type'] == 'checkbox') {
        if ($atts['value']) {
            $atts['checked'] = 'checked';
        }
        $atts['value'] = true;
    }
    if (isset($options['type']) && $options['type'] == 'url') {
        $atts['type'] = 'url';
        $atts['class'] = 'regular-text code';
    }
    array_walk($atts, function(&$item, $key) {
        $item = sprintf('%s="%s"', esc_attr($key), esc_attr($item));
    } );

    require EBSCO_WIDGET__VIEW . 'settings.php';
};

/**
 * Validate the settings saved.
 *
 * @param array $input
 * @return array
 */
$settings_validate = function ($input)
{
    $errors = array();
    foreach ($input AS $key => $value) {
        if ($value == '') {
            unset($input[$key]);
            continue;
        }
        $validator = false;
        if (isset( $this->settings[$key]['validator'])) {
            $validator = $this->settings[$key]['validator'];
        }
        switch ($validator) {
            case 'url':
                $pattern = '/^http(s)?:\/\/.*$/';
                if (preg_match($pattern, $value)) {
                    $input[$key] = $value;
                } else {
                    $errors[] = sprintf('%s doit être un url valide.', $key);
                    unset($input[$key]);
                }
            break;
            default:
                 $input[$key] = strip_tags($value);
            break;
        }
    }
    if (count($errors) > 0) {
        add_settings_error(
            $this->tag,
            $this->tag,
            implode('<br />',$errors),
            'error'
        );
    }
    return $input;
};

/**
 * Add the setting fields to the Reading settings page.
 *
 */
$admin_init = function () use($settings_field, $settings_validate)
{
    require EBSCO_WIDGET__PATH . 'config.php';
    $section = 'general';
    add_settings_section(
        $config->tag . '_settings_section',
        sprintf('Réglages pour %s', $config->name),
        function () use ($config) {
            echo sprintf('<p>Options de configurations pour le plugin %s.</p>', esc_html($config->name));
        },
        $section
    );
    foreach ($config->settings AS $id => $options) {
        $options['id'] = $id;
        add_settings_field(
            sprintf('%s_%s_settings', $config->tag, $id),
            $id,
            $settings_field,
            $section,
            $config->tag . '_settings_section',
            $options
        );
    }
    register_setting(
        $section,
        $config->tag,
        $settings_validate
    );
};
