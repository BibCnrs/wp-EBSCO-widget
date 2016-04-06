<?php
/**
 * [ebsco-widget] shortcode action
 *
 * @param array $atts
 * @param string $content
 * @return string
 */
$getShortcode = function ($config) {
    return function ($atts, $content = null, $a) use ($config)
    {
        $options = get_option($config->tag);
        $domain = $atts['domain'];
        // Define the URL path to the plugin...
        // Enqueue the styles in they are not already...
        if (!wp_style_is($config->tag, 'enqueued')) {

            wp_register_style(
                $config->tag,
                $config->url . 'node_modules/ebsco-widget/build/app.css'
            );

            wp_register_style(
                $config->tag . '-loader',
                $config->url . 'css/loader.css'
            );
            wp_enqueue_style($config->tag);
            wp_enqueue_style($config->tag . '-loader');
        }

        // Enqueue the scripts if not already...
        if (!wp_script_is($config->tag, 'enqueued')) {
            $string = file_get_contents($config->home . "node_modules/ebsco-widget/package.json");
            $json = json_decode($string, true);
            wp_register_script(
                $config->tag,
                $config->url . 'node_modules/ebsco-widget/build/app.js',
                [],
                $json['version'],
                true
            );
            wp_register_script(
                $config->tag.'-index',
                $config->url . 'javascripts/index.js',
                ['jquery', $config->tag],
                $config->version,
                true
            );
            $term = urldecode(get_query_var('search_term'));
            // add url attribute on script tag
            add_filter('script_loader_tag', function ( $tag, $handle ) use ($term, $domain, $config, $options) {
                if ( $handle !== 'ebsco_widget-index' ) return $tag;
                $addedAttr = sprintf(' id="%s" data-url="%s" data-term="%s" data-domain="%s" src', $handle, $options['url'], $term, $domain);

                return str_replace(' src', $addedAttr, $tag);
            }, 10, 2);
            wp_enqueue_script('ebsco_widget-index');
        }

        require $config->views . 'shortcode.php';
    };
};
