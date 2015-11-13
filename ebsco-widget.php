<?php
/*
Plugin Name: EbscoWidget
Plugin URI: TODO
Description: Integrates the react EbscoWidget plugin into your WordPress install.
Version: 0.1
Author: BibCnrs
Author URI: https://github.com/BibCnrs/BibCnrs
*/

defined('ABSPATH') or die('Plugin file cannot be accessed directly.');

require_once 'config.php';

// in development
if (file_exists($config->home . '/vendor/autoload.php')) {
    require $config->home . '/vendor/autoload.php';
}
// when loaded as a plugin
if (file_exists(realpath($config->home.'../../../').'/vendor/autoload.php')) {
    require realpath($config->home.'../../../').'/vendor/autoload.php';
}

$init = function () use($config) {
    require $config->actions . 'shortcode.php';
    add_filter('query_vars', function ($vars) {
        $vars[] = 'search_term';
        return $vars;
    });
    add_shortcode($config->tag, $getShortcode($config));
    if (is_admin()) {
        require $config->actions . 'admin_init.php';
        add_action('admin_init', $getAdminInit($config));
    }
};

add_action('init', $init);
