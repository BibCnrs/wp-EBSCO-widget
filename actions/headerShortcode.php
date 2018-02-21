<?php
/**
 * [ebsco-widget-header] shortcode action
 *
 * @param array $atts
 * @param string $content
 * @return string
 */
$getHeaderShortcode = function ($config) {
    return function ($atts, $content = null, $a) use ($config)
    {
        require $config->views . 'headerShortcode.php';
    };
};
