<?php
/*
Plugin Name: EbscoWidget
Plugin URI: TODO
Description: Integrates the react EbscoWidget plugin into your WordPress install.
Version: 0.1
Author: BibCnrs
Author URI: https://github.com/BibCnrs/BibCnrs
*/

namespace WpEbscoWidget\Classes;

use \Firebase\JWT\JWT;

class EbscoWidget
{
    /**
     * Tag identifier used by file includes.
     * @var string
     */
    protected $tag = 'ebsco_widget';

    /**
     * User friendly name used to identify the plugin.
     * @var string
     */
    protected $name = 'EBBSCO Widget';

    /**
     * Current version of the plugin.
     * @var string
     */
    protected $version = '0.1';

    /**
     * List of settings displayed on the admin settings page.
     * @var array
     */
    protected $settings = array('url' => array(
        'description' => 'Url pour accéder à BibCnrs Api.',
        'validator' => 'url',
        'type' => 'url',
        'placeholder' => 'http://BibCnrsHost'
    ),
    'secret' => array(
        'description' => 'Secret to encode jwt token used by the widget to access the api.',
        'type' => 'password',
        'placeholder' => ''
    ));

    public function __construct()
    {
        if ($options = get_option($this->tag)) {
            $this->options = $options;
        }
        add_shortcode($this->tag, array(&$this, 'shortcode'));
        if (is_admin()) {
            add_action('admin_init', array(&$this, 'settings'));
        }
    }

    /**
     * Allow the ebsco-widget shortcode to be used.
     *
     * @access public
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function shortcode($atts, $content = null)
    {
        extract(shortcode_atts(array(), $atts));
        $this->_enqueue();

        require EBSCO_WIDGET__VIEW_PATH . 'shortcode.php';
    }

    /**
     * Add the setting fields to the Reading settings page.
     *
     * @access public
     */
    public function settings()
    {
        $section = 'general';
        add_settings_section(
            $this->tag . '_settings_section',
            'Réglages pour ' . $this->name,
            function () {
                echo '<p>Options de configurations pour le plugin ' . esc_html( $this->name ) . '.</p>';
            },
            $section
        );
        foreach ( $this->settings AS $id => $options ) {
            $options['id'] = $id;
            add_settings_field(
                $this->tag . '_' . $id . '_settings',
                $id,
                array( &$this, 'settings_field' ),
                $section,
                $this->tag . '_settings_section',
                $options
            );
        }
        register_setting(
            $section,
            $this->tag,
            array( &$this, 'settings_validate' )
        );
    }

    /**
     * Append a settings field to the the fields section.
     *
     * @access public
     * @param array $args
     */
    public function settings_field( array $options = array() )
    {
        $atts = array(
            'id' => $this->tag . '_' . $options['id'],
            'name' => $this->tag . '[' . $options['id'] . ']',
            'type' => ( isset( $options['type'] ) ? $options['type'] : 'text' ),
            'class' => 'small-text',
            'value' => ( array_key_exists( 'default', $options ) ? $options['default'] : null )
        );
        if ( isset( $this->options[$options['id']] ) ) {
            $atts['value'] = $this->options[$options['id']];
        }
        if ( isset( $options['placeholder'] ) ) {
            $atts['placeholder'] = $options['placeholder'];
        }
        if ( isset( $options['type'] ) && $options['type'] == 'checkbox' ) {
            if ( $atts['value'] ) {
                $atts['checked'] = 'checked';
            }
            $atts['value'] = true;
        }
        if ( isset( $options['type'] ) && $options['type'] == 'url' ) {
            $atts['type'] = 'url';
            $atts['class'] = 'regular-text code';
        }
        array_walk( $atts, function( &$item, $key ) {
            $item = esc_attr( $key ) . '="' . esc_attr( $item ) . '"';
        } );

        require EBSCO_WIDGET__VIEW_PATH . 'settings.php';
    }

    /**
     * Validate the settings saved.
     *
     * @access public
     * @param array $input
     * @return array
     */
    public function settings_validate($input)
    {
        $errors = array();
        foreach ($input AS $key => $value) {
            if ($value == '') {
                unset($input[$key]);
                continue;
            }
            $validator = false;
            if (isset( $this->settings[$key]['validator'])) {
                $validator = $this->settings[$key]['validator'];
            }
            switch ($validator) {
                case 'url':
                    $pattern = '/^http(s)?:\/\/.*$/';
                    if ( preg_match($pattern, $value) ) {
                        $input[$key] = $value;
                    } else {
                        $errors[] = $key . ' doit être un url valide.';
                        unset( $input[$key] );
                    }
                break;
                default:
                     $input[$key] = strip_tags( $value );
                break;
            }
        }
        if (count($errors) > 0) {
            add_settings_error(
                $this->tag,
                $this->tag,
                implode('<br />',$errors),
                'error'
            );
        }
        return $input;
    }

    protected function _enqueue()
    {
        // Define the URL path to the plugin...
        // Enqueue the styles in they are not already...
        if (!wp_style_is($this->tag, 'enqueued')) {

            wp_register_style(
                $this->tag,
                EBSCO_WIDGET__PLUGIN_URL . 'node_modules/ebsco-widget/build/app.css'
            );
            wp_enqueue_style($this->tag);
        }

        // Enqueue the scripts if not already...
        if (!wp_script_is($this->tag, 'enqueued')) {
            wp_register_script(
                $this->tag,
                EBSCO_WIDGET__PLUGIN_URL . 'node_modules/ebsco-widget/build/app.js',
                array(),
                $this->version,
                true
            );
            wp_register_script(
                $this->tag.'-index',
                EBSCO_WIDGET__PLUGIN_URL . "javascripts/index.js",
                array('jquery', $this->tag),
                $this->version,
                true
            );
            $token = null;
            if (session_id()) {
                $token = JWT::encode(array(user => 'tester'), $this->options['secret']);
            }
            $term = get_query_var('term');
            // add url attribute on script tag
            add_filter( 'script_loader_tag', function ( $tag, $handle ) use ($term, $token) {
                if ( $handle !== 'ebsco_widget-index' ) return $tag;
                return str_replace(' src', ' id="' . $handle . '" data-url="' . ($this->options['url']) . '" data-term="' . $term . '" data-token="' . $token . '" src', $tag );
            }, 10, 2 );
            wp_enqueue_script($this->tag . '-index');
        }
    }
}
