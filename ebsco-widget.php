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

define('EBSCO_WIDGET__PLUGIN_PATH', plugin_dir_path(__FILE__));
define('EBSCO_WIDGET__PLUGIN_URL', plugin_dir_url(__FILE__));
define('EBSCO_WIDGET__VIEW_PATH', EBSCO_WIDGET__PLUGIN_PATH . 'views' . DIRECTORY_SEPARATOR);

require_once(EBSCO_WIDGET__PLUGIN_PATH . 'bootstrap.php');

use \Firebase\JWT\JWT;
use WpEbscoWidget\classes\EbscoWidget;

new EbscoWidget();
