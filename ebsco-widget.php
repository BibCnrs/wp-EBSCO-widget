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

define('EBSCO_WIDGET__PATH', plugin_dir_path(__FILE__));
define('EBSCO_WIDGET__URL', plugin_dir_url(__FILE__));
define('EBSCO_WIDGET__VIEW', EBSCO_WIDGET__PATH . 'views' . DIRECTORY_SEPARATOR);

require_once(EBSCO_WIDGET__PATH . 'bootstrap.php');

function init() {
    require EBSCO_WIDGET__PATH . 'config.php';
    require EBSCO_WIDGET__PATH . 'actions' . DIRECTORY_SEPARATOR . 'shortcode.php';
    add_filter('query_vars', function ($vars) {
        $vars[] = 'search_term';
        return $vars;
    });
    add_shortcode($config->tag, $shortcode);
    if (is_admin()) {
        require EBSCO_WIDGET__PATH . 'actions' . DIRECTORY_SEPARATOR . 'admin_init.php';
        add_action('admin_init', $admin_init);
    }
}

add_action('init', 'init');
