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

// we look for Composer files first in the plugins dir.
// then in the wp-content dir (site install).
// and finally in the current themes directories.
if (   file_exists( $composer_autoload = __DIR__ . '/vendor/autoload.php' ) /* check in self */
	|| file_exists( $composer_autoload = WP_CONTENT_DIR.'/vendor/autoload.php') /* check in wp-content */
	|| file_exists( $composer_autoload = plugin_dir_path( __FILE__ ).'vendor/autoload.php') /* check in plugin directory */
	|| file_exists( $composer_autoload = get_stylesheet_directory().'/vendor/autoload.php') /* check in child theme */
	|| file_exists( $composer_autoload = get_template_directory().'/vendor/autoload.php') /* check in parent theme */
	) {
	require_once $composer_autoload;
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
