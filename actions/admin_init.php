<?php

require_once 'add-settings-field.php';
require_once 'validate-settings.php';

/**
 * Add the setting fields to the Reading settings page.
 *
 */
$getAdminInit = function ($config) use ($getAddSettingsField, $getValidateSettings) {
    $addSettingsField = $getAddSettingsField($config);
    $validateSettings = $getValidateSettings($config);
    return function () use($config, $addSettingsField, $validateSettings)
    {
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
};
