<?php
/**
 * [ebsco-widget-connection] shortcode action
 *
 * @param array $atts
 * @param string $content
 * @return string
 */
$getConnectionShortcode = function ($config) {
    return function ($atts, $content = null, $a) use ($config)
    {
        require $config->views . 'connectionShortcode.php';
    };
};
