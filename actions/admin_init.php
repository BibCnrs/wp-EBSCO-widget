<?php

/**
 * Append a settings field to the fields section.
 *
 * @param array $args
 */
$addSettingsField = function ( array $options = array() )
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
    if (isset($options['type']) && $options['type'] == 'url') {
        $atts['type'] = 'url';
        $atts['class'] = 'regular-text code';
    }
    array_walk($atts, function(&$item, $key) {
        $item = sprintf('%s="%s"', esc_attr($key), esc_attr($item));
    } );

    require EBSCO_WIDGET__VIEW . 'settings.php';
};

require 'validate-settings.php';

/**
 * Add the setting fields to the Reading settings page.
 *
 */
$admin_init = function () use($addSettingsField, $validateSettings)
{
    require EBSCO_WIDGET__PATH . 'config.php';
    $section = 'general';
    $settingSection = sprintf('Réglages pour %s', $config->name);
    add_settings_section(
        $settingSection,
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
            $addSettingsField,
            $section,
            $settingSection,
            $options
        );
    }
    register_setting(
        $section,
        $config->tag,
        $validateSettings
    );
};
