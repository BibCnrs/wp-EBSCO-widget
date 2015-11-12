<?php

/**
 * [ebsco-widget] shortcode action
 *
 * @param array $atts
 * @param string $content
 * @return string
 */
$shortcode = function ($atts, $content = null)
{
    require EBSCO_WIDGET__PATH . 'config.php';
    $options = get_option($config->tag);
    extract(shortcode_atts(array(), $atts));
    // Define the URL path to the plugin...
    // Enqueue the styles in they are not already...
    if (!wp_style_is($config->tag, 'enqueued')) {

        wp_register_style(
            $config->tag,
            EBSCO_WIDGET__URL . 'node_modules/ebsco-widget/build/app.css'
        );
        wp_enqueue_style($config->tag);
    }

    // Enqueue the scripts if not already...
    if (!wp_script_is($config->tag, 'enqueued')) {
        wp_register_script(
            $config->tag,
            EBSCO_WIDGET__URL . 'node_modules/ebsco-widget/build/app.js',
            array(),
            $config->version,
            true
        );
        wp_register_script(
            $config->tag.'-index',
            EBSCO_WIDGET__URL . "javascripts/index.js",
            array('jquery', $config->tag),
            $config->version,
            true
        );
        $token = null;
        if (session_id()) {
            $token = JWT::encode(array(user => 'tester'), $options['secret']);
        }
        $term = get_query_var('term');
        // add url attribute on script tag
        add_filter('script_loader_tag', function ( $tag, $handle ) use ($term, $token) {
            if ( $handle !== 'ebsco_widget-index' ) return $tag;
            $addedAttr = sprintf(' id="%s" data-url="%s" data-term="%s" data-token="%s" src', $handle, $options['url'], $term, $token);

            return str_replace(' src', $addedAttr, $tag);
        }, 10, 2);
        wp_enqueue_script($config->tag . '-index');
    }
    require EBSCO_WIDGET__VIEW . 'shortcode.php';
};
