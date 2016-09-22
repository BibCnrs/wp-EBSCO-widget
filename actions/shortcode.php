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
        $language = $atts['language'] ? $atts['language'] : 'fr';
        $string = file_get_contents($config->home . "node_modules/ebsco-widget/package.json");
        $json = json_decode($string, true);
        // Define the URL path to the plugin...
        // Enqueue the styles in they are not already...
        if (!wp_style_is($config->tag, 'enqueued')) {

            wp_register_style(
                $config->tag,
                $config->url . 'node_modules/ebsco-widget/build/app.css',
                [],
                $json['version']
            );

            wp_register_style(
                $config->tag . '-loader',
                $config->url . 'css/loader.css'
            );
            wp_enqueue_style($config->tag);
            wp_enqueue_style($config->tag . '-loader');
        }

        if(!wp_script_is('babel-polyfill', 'enqueued')) {
            wp_register_script(
                'babel-polyfill',
                $config->url . 'node_modules/ebsco-widget/node_modules/babel-polyfill/dist/polyfill.min.js',
                [],
                '6.3.14'
            );
        }

        if(!wp_script_is('react', 'enqueued')) {
            wp_register_script(
                'react',
                $config->url . 'node_modules/ebsco-widget/node_modules/react/dist/react-with-addons.min.js',
                [],
                '0.14.3'
            );
        }

        if(!wp_script_is('react-dom', 'enqueued')) {
            wp_register_script(
                'react-dom',
                $config->url . 'node_modules/ebsco-widget/node_modules/react-dom/dist/react-dom.min.js',
                ['react'],
                '0.14.3'
            );
        }
        // Enqueue the scripts if not already...
        if (!wp_script_is($config->tag, 'enqueued')) {
            wp_register_script(
                $config->tag,
                $config->url . 'node_modules/ebsco-widget/build/app.js',
                ['babel-polyfill', 'react', 'react-dom'],
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
            // add url attribute on script tag
            add_filter('script_loader_tag', function ( $tag, $handle ) use ($term, $domain, $language, $config, $options) {
                if ( $handle != 'ebsco_widget-index' ) return $tag;
                $addedAttr = sprintf(
                    ' id="%s" data-url="%s" data-db_url="%s" data-domain="%s" data-language="%s" src',
                    $handle, $options['url'], $options['db_url'], $domain, $language
                );

                return str_replace(' src', $addedAttr, $tag);
            }, 10, 2);
            wp_enqueue_script('ebsco_widget-index');
        }

        require $config->views . 'shortcode.php';
    };
};
