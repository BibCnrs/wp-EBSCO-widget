<?php
use \Firebase\JWT\JWT;
/**
 * [ebsco-widget] shortcode action
 *
 * @param array $atts
 * @param string $content
 * @return string
 */
$getShortcode = function ($config) {
    return function ($atts, $content = null) use ($config)
    {
        $options = get_option($config->tag);
        extract(shortcode_atts(array(), $atts));
        // Define the URL path to the plugin...
        // Enqueue the styles in they are not already...
        if (!wp_style_is($config->tag, 'enqueued')) {

            wp_register_style(
                $config->tag,
                $config->url . 'node_modules/ebsco-widget/build/app.css'
            );
            wp_enqueue_style($config->tag);
        }

        // Enqueue the scripts if not already...
        if (!wp_script_is($config->tag, 'enqueued')) {
            wp_register_script(
                $config->tag,
                $config->url . 'node_modules/ebsco-widget/build/app.js',
                array(),
                $config->version,
                true
            );
            wp_register_script(
                $config->tag.'-index',
                $config->url . 'javascripts/index.js',
                array('jquery', $config->tag),
                $config->version,
                true
            );
            $token = null;
            if (is_user_logged_in()) {
                $token = JWT::encode(array('user' => 'tester'), $options['secret']);
            }
            $term = urldecode(get_query_var('search_term'));
            // add url attribute on script tag
            add_filter('script_loader_tag', function ( $tag, $handle ) use ($term, $token, $config, $options) {
                if ( $handle !== 'ebsco_widget-index' ) return $tag;
                $addedAttr = sprintf(' id="%s" data-url="%s" data-term="%s" data-token="%s" src', $handle, $options['url'], $term, $token);

                return str_replace(' src', $addedAttr, $tag);
            }, 10, 2);
            wp_enqueue_script('ebsco_widget-index');
        }

        require $config->views . 'shortcode.php';
    };
};
