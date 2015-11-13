<?php

/**
 * Append a settings field to the fields section.
 *
 * @param array $args
 */
$getAddSettingsField = function ($config) {
    return function (array $options = array()) use ($config)
    {
        $pluginOptions = get_option($config->tag);
        $atts = [
            'id' => sprintf('%s_%s' , $config->tag, $options['id']),
            'name' => sprintf('%s[%s]', $config->tag, $options['id']),
            'type' => (isset( $options['type'] ) ? $options['type'] : 'text'),
            'class' => 'regular-text',
            'value' => (array_key_exists('default', $options) ? $options['default'] : null)
        ];
        if (isset( $pluginOptions[$options['id']])) {
            $atts['value'] = $pluginOptions[$options['id']];
        }
        if (isset( $options['placeholder'])) {
            $atts['placeholder'] = $options['placeholder'];
        }
        if (isset($options['type']) && $options['type'] == 'url') {
            $atts['type'] = 'url';
        }
        array_walk($atts, function(&$item, $key) {
            $item = sprintf('%s="%s"', esc_attr($key), esc_attr($item));
        });

        $description = $options['description'];

        require $config->views . 'settings.php';
    };
};
