<?php
bd_log("#BOF");

//error_reporting(-1);
//ini_set('display_errors', 'On');

/*
 * Plugin Name: tibit — pocket change for the internet;
//ini_set('
 * Plugin URI: http://www.tibdit.com
 * Description: Collect tibs from readers.
 * Version: 1.6.5
 * Author: Justin Maxwell / Jim Smith / Laxyo Solution Softs Pvt Ltd.
 * Author URI:
 * Text Domain: tibit
 * Domain Path:
 * Domain Path:
 * License: GPL3
 */

/*  Copyright (C) 2014 tibdit limited

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    See <http://www.gnu.org/licenses/> for the full text of the
    GNU General Public License.
*/


define( 'TIBDIT_VERSION', '1.6.5' );
define( 'TIBDIT_RELEASE_DATE', date_i18n( 'F j, Y', '1397937230' ) );
define( 'TIBDIT_DIR', plugin_dir_path( __FILE__ ) );
define( 'TIBDIT_URL', plugin_dir_url( __FILE__ ) );

$svg_button_heights = array(
    "bubble" => 55,
    "chevron" => 25,
    "coin" => 25,
    "hex" => 25,
    "horiz" => 20,
    "poster" => 50,
    "shadow" => 20,
    "default" => 31,
    "vert" => 61
);

$bd_default_settings = array
(
    // Plugin wide button settings - overridden by location-specific button settings
    'PAD' => '',
    'ASN' => '',
    'BTN' => "default",
    'DUR' => 1,
    'BTC' => '#2c82c9',
    'BTH' => '',
    'tib_mode' => 'PAD',
    'title' => 'tibit',
    'intro' => '<p>If you found this blog useful or interesting, please consider giving me a tib.</p><p>Tibs are pocket-change for the internet™.</p>',
    'caption' => 'If you found this post useful or interesting, please consider giving me a tib. <br> Tibs are pocket-change for the internet™.',

    // Location Specific Button Settings

    // Post location
    'post' => array(
        'PAD' => '',
        'ASN' => '',
        'BTN' => 'horiz',
        'BTH' => $svg_button_heights['horiz']
    ),

    'widget' => array(
        'PAD' => '',
        'ASN' => '',
        'BTN' => 'shadow',
        'BTH' => $svg_button_heights['shadow']
    ),

    'shortcode' => array(
        'PAD' => '',
        'ASN' => '',
        'BTN' => 'shadow',
        'BTH' => $svg_button_heights['shadow']
    ),

    // Plugin-wide settings
    'append_after_content' => "true",
    'append_before_content' => "false",
    'append_only_on_single' => "false",
    'advanced_settings' => "false",
    'TIB_QTYs' => ''
);

register_activation_hook( __FILE__, 'activate' );

/**
 * Plugin Activation hook function to check for Minimum PHP and WordPress versions
 * @param string $wp Minimum version of WordPress required for this plugin
 * @param string $php Minimum version of PHP required for this plugin
 */
function activate() {


    $wp = '3.3'; $php = '5.2.6';
    global $wp_version;

    if ( version_compare( PHP_VERSION, $php, '<' ) )
        $flag = 'PHP';
    elseif
    ( version_compare( $wp_version, $wp, '<' ) )
        $flag = 'WordPress';
    else
        return;
    $version = 'PHP' == $flag ? $php : $wp;
    deactivate_plugins( basename( __FILE__ ) );
    wp_die('<p>The <strong>tibit</strong> plugin requires '.$flag.'  version '.$version.' or greater.</p>','Plugin Activation Error',  array( 'response'=>200, 'back_link'=>TRUE ) );
}


if (!function_exists('is_admin'))
{
    bd_log("not admin");
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}


// include 'ChromePhp.php';
include_once('helper-functions.php');
$plugurl = plugin_dir_url( __FILE__ );
$image_resource_url = $plugurl."resources".DIRECTORY_SEPARATOR."images";
$stylesheet_resource_url = $plugurl."resources".DIRECTORY_SEPARATOR."styles";
$javascript_resource_url = $plugurl."resources".DIRECTORY_SEPARATOR."javascripts";

/**
 * Register the javascripts files which can be used at a later time
 *
 * filetype is used to ensure that the latest version of the file is used
 * regardless of caching.
 *
 * @param $name the name of the javascript filename
 * @param bool|false $in_footer the files would be added to the header only
 * @param array $deps can be used if a particular script is depended on another script.
 */

function bd_register_script( $name, $in_footer=false, $deps=array())
{
    global $javascript_resource_url;
    $filename= plugin_dir_path( __FILE__ )."resources/javascripts/".$name.'.js';
    $fileurl= $javascript_resource_url.DIRECTORY_SEPARATOR.$name.'.js';
    wp_register_script( 'bd-'.$name, $fileurl, $deps, filemtime( $filename ), $in_footer);
    ## log this fully to find out why --bottom.js not queued at end
    bd_log( $fileurl);
}


/**
 * Register a CSS style file for later use with wp_enqueue_style().
 *
 * @param $name the name of the style sheet
 * @param array $deps if this stylesheet is dependent on another stylesheet
 */
function bd_register_style( $name, $deps=array())
{
    global $stylesheet_resource_url;
    $filename= plugin_dir_path( __FILE__ )."resources/styles/".$name.'.css';
    $fileurl= $stylesheet_resource_url.DIRECTORY_SEPARATOR.$name.'.css';
    wp_register_style( 'bd-'.$name, $fileurl, $deps, filemtime( $filename ));
    bd_log( $fileurl);
}

if (!class_exists("tibditWidget"))
{
    bd_log( "no tibditWidget:: class exists");

    class tibditWidget extends WP_Widget {
        var $hastibbed = false;
        var $settings, $options_page;

        function __construct()
        {

            $this->settings_map = array(
                'payaddr' => 'PAD',
                'acktime' => 'DUR',
                'bd_button' => 'BTN',
                'bd_base_colour' => 'BTC',
                'bd_button_scale' => 'BTH',
                'subref' => 'SUB'
            );

            $this->default_settings = $GLOBALS['bd_default_settings'];

            bd_log("tibditWidget:: __construct");
            $this->settings_field = 'tibdit_options';
            $this->svg_button_heights = $GLOBALS['svg_button_heights'];

            parent::__construct( 'tibdit_widget', 'tibit', array( 'description' => __( 'Collect tibs from readers', 'tibit' )));

            if (is_admin())
            {
                bd_log("tibditWidget:: __construct admin");
                // Load example settings page
                if (!class_exists("tibdit_settings")) {
//                    bd_log("before include $TIBDIT_DIR");
                    include(TIBDIT_DIR . 'tibdit-settings.php');
                    bd_log("AFTER INCL");
                }
                bd_log("after ! class exists");
                $this->settings = new tibdit_settings();
                bd_log("after new");
            }

            include(TIBDIT_DIR . 'AddressValidator.php');

            add_action('init', array($this,'init') );
            add_action('admin_init', array($this,'admin_init') );
            // add_action('admin_menu', array($this,'add_admin_menu') );
//            add_action('wp_head', 'bd_get_tib_token');
            add_action('wp_enqueue_scripts', array($this,'tibdit_plugin_enqueue') );
            add_filter('query_vars', array($this,'add_query_vars_filter') );

            add_shortcode('tib_site', array($this,'tib_shortcode_func') );
            add_shortcode('tib_post', array($this,'tib_shortcode_func') );
            add_shortcode('tib', array($this, 'tib_shortcode_func'));
            add_shortcode('tib_inline', array($this,'tib_shortcode_func') );

            add_filter('the_content', 'insert_after_post');


        }

        function init() {


            $options = get_site_option('tibdit_options');
            $options = wp_parse_args($options, $this->default_settings);
//          getting the array of current options from wordpress and saving to $options
            bd_log('init: ' . var_export($options, true));

            bd_log('init: default settings are: ' . var_export($this->default_settings, true));

            $options['BTH'] = $this->map_new_button_height($options['BTH']);

            if(!isset($options['last_known_version'])){
//          if there is no value for last_known_version, set it to the constant specified when the plugin runs,
//          and then fill out $options with defaults
                bd_log('init: no version found on record (first load?)');
                $options['last_known_version'] = TIBDIT_VERSION;

                /* FUNCTIONS RELATING TO UPDATE FROM 1.4.6 -> 1.5 */
                $this->map_new_widget_options();
                $options = $this->map_new_settings_names($options, $this->settings_map);
                $options['BTN'] = $this->map_new_button_names($options['BTN']);
                $options['BTH'] = $this->map_new_button_height($options['BTH']);
                /* END FUNCTIONS RELATING TO UPDATE FROM 1.4.6 -> 1.5 */

            }

            if(version_compare($options['last_known_version'], TIBDIT_VERSION, "<")){
//          if the current version is more recent than the version on record, check the options against defaults
//          to ensure each field has values
                bd_log('init: this version (' . TIBDIT_VERSION . ') is newer than the one on record (' .
                    $options['last_known_version'] . ')');
                $options['last_known_version'] = TIBDIT_VERSION;

                /* FUNCTIONS RELATING TO UPDATE FROM 1.4.6 -> 1.5 */
                $this->map_new_widget_options();
                $options = $this->map_new_settings_names($options, $this->settings_map);
                $options['BTN'] = $this->map_new_button_names($options['BTN']);
                $options['BTH'] = $this->map_new_button_height($options['BTH']);
                /* END FUNCTIONS RELATING TO UPDATE FROM 1.4.6 -> 1.5 */

                $options = wp_parse_args($options, $this->default_settings);
            }

//            $options = wp_parse_args($options, $GLOBALS["default_settings"]);

            update_option('tibdit_options', $options);
        }

        function map_new_button_height($BTH)
            /* Used to update the value of BTH when coming from 1.4.6 -> 1.5. If we're coming from 1.4.6, the value
            of BTH should be between 0.5 and 1.3, far below the new minimum value of 25. If so, we set the BTH to the
            default value specified in the global default_settings array */
        {
            if (isset($BTH)) {
                if (intval($BTH) < 25) {
                    return $this->default_settings['BTH'];
                }
                else{
                    return $BTH;
                }
            }
            else{
                return $BTH;
            }
        }

        function map_new_widget_options(){
            /* Updating widget settings to match new setting keys, using map_new_settings_names() to produce
            an array with the new keys */
            $widget_options_array = get_option($this->option_name);
            bd_log('init() widget_options_array: ' . var_export($widget_options_array, true));
            $widget_options_array[$this->number] = $this->map_new_settings_names($widget_options_array[$this->number],
                $this->settings_map);
            bd_log('init() widget_options_array: ' . var_export($widget_options_array, true));
            update_option($this->option_name, $widget_options_array);
        }

        function map_new_button_names($current_button){
            /* Takes a string as input,  */
            switch($current_button){
                case "RectButton":
                    return 'shadow';
                case "SoapButton":
                    return 'default';
                case "CoinButton":
                    return 'coin';
                case "HexButton":
                    return 'hex';
                case "InverseButton":
                    return 'poster';
                case "TicketButton":
                    return 'chevron';
                case "BubbleButton";
                    return 'bubble';
                case "HorizontalButton":
                    return 'horiz';
                case "VerticalButton":
                    return 'vert';
                default:
                    return $current_button;
            }
        }

        function map_new_settings_names($settings, $settings_map){
            /* Takes two arguments, $settings and $settings_map. Each element in $settings_map
            represents an $new_setting => $old_setting key pair. We search for old_setting keys in $settings, and write
            them to key new_settings, deleting the old_settings as we go */

            $mapped_array = $settings;
            bd_log('||map_new_settings_names $mapped_array initial values' . var_export($mapped_array, true));

            if(isset($mapped_array)){

                foreach($settings_map as $old_settings_key => $new_settings_key){
                    if(array_key_exists($old_settings_key, $settings)){
                        $mapped_array[$new_settings_key] = $settings[$old_settings_key];
                        unset($mapped_array[$old_settings_key]);
                    }
                }

            }

            bd_log('||map_new_settings_names $mapped_array final values' . var_export($mapped_array, true));
            return $mapped_array;
        }

        function admin_init() {}

        // function add_admin_menu()
        // {
        // 	add_menu_page( 'tib config', 'tibdit', 'administrator', 'tibdit', 'admin_page', plugin_dir_url( __FILE__ ).'admin_icon.png' );
        // }


        function add_query_vars_filter( $vars )           // For query variable
        {
            bd_log("tibditWidget::add_query_vars_filter()");
            $vars[] = "tibdit";
            return $vars;
        }


        function tibdit_plugin()
        {
            bd_log("tibditWidget::tibdit_plugin()");
            parent::WP_Widget(false, $name = 'tibdit_widget');
        }


        function form($instance)    // widget form creation
        {
//             echo "top of the form to ya";

            $options = get_option( $this->settings_field );
            bd_log("tibditWidget::form()");
            if( $instance)          // Check values
            {
                bd_log("form() has instance " . var_export($instance, true));
                $instance = $this->map_new_settings_names($instance, $this->settings_map);
            }
            else
            {
                bd_log( "form() no instance - options " . var_export($instance, true));
                $instance = wp_parse_args($instance, get_option('tibdit_options'));
                $instance['SUB'] = "WP_Widget";
                $instance['colour'] = "";
                bd_log("form() no instance - defaults set " . var_export($instance, true));   //default content for widget settings on Apearance/Widget page
            }

            $setting = array
            (
                "title" => "Heading",
                "intro" => "Caption",
                "SUB" => "Subreference",
                "colour" => "Background tint"
            );


            foreach ($setting as $key => $label)
            {
                $item = array
                (
                    "ckey" => $key,
                    "label" => _e($label, 'tibdit'),
                    "fname" => $this->get_field_name($key),
                    "fid" => $this->get_field_id($key),
                    "value" => esc_textarea($instance[$key])
                );
                echo "<p><label for=$item[fid]>$item[label]</label>";
                echo "<input type=text id='$item[fid]' name='$item[fname]' value='$item[value]'";
                switch ($key)
                {
                    case 'title':
                    case 'intro':
                    case 'SUB':
                        echo " >";
                        break;
                    case 'colour':
                        echo " class='bd bd-colourp' data-default-color=''>";
                        break;
                }
                echo "</p>";
            }
        }



        function update($new_instance, $old_instance)
        {

            $default_settings = $this->default_settings;

            $options = get_option( $this->settings_field );

            bd_log( "tibditWidget::update() old instance:" . var_export($old_instance, true));
            bd_log( "tibditWidget::update() new instance :" . var_export($new_instance, true));
            $instance = array();

            if (isset($new_instance['title']) && $new_instance['title'])
            {
                bd_log("update() has title");
                $instance['title'] = strip_tags($new_instance['title']);
                $instance['title'] = preg_replace_callback("/([Tt][iI][bB])([^ACE-Zace-z][\w]*|$)/", array($this, 'tibtolower'), $new_instance['title']);
            }
            elseif (isset($options['title']) && $options['title'])
            {
                bd_log("update() no title but option title found");
                $instance['title'] = $options['title'];
            }
            else
            {
                bd_log("update() no title using default");
                $instance['title'] = $default_settings['title'];
            }

            if (isset($new_instance['intro']) && $new_instance['intro'])
            {
                $instance['intro'] = strip_tags($new_instance['intro']);
                $instance['intro'] = preg_replace_callback("/([Tt][iI][bB])([^ACE-Zace-z][\w]*|$)/", array($this, 'tibtolower'), $new_instance['intro']);
            }
            elseif (isset($options['intro']) && $options['intro'])
                $instance['intro'] = $options['intro'];
            else
                $instance['intro'] = $default_settings['intro'];

            if (isset($new_instance['colour']) && $new_instance['colour'])
                $instance['colour'] = strip_tags($new_instance['colour']);
            elseif ($options['colour'])
                $instance['colour'] = $options['colour'];
            else
                $instance['colour'] = $default_settings['colour'];

            if(isset($new_instance['BTH']) && $new_instance['BTH']){
//          if a new value for scale is fed into the update function, update $instance to reflect this
                $instance['BTH'] = $new_instance['BTH'];
            }
            elseif (isset($options['BTH']) && $options['BTH']){
//          If there is no new value, but there is a pre-set value in the options, feed this value to $instance
                $instance['BTH'] = $options['BTH'];
            }
            else{
//            if there is no new value, and no pre-set value, fall back to the default value
                $instance['BTH'] = $default_settings['BTH'];
            }


            // $instance['PAD'] = strip_tags($new_instance['PAD']);
            $instance['PAD'] = $options['PAD'];
            $instance['SUB'] = strip_tags($new_instance['SUB']);

            bd_log( "tibditWidget::update() end instance" . var_export($instance, true));

            return $instance;
        }

        function tibtolower($matches)
        {
            return strtolower($matches[0]);
        }

        function widget($args, $instance) // non shortcode widget output
        {
            $options = get_option( $this->settings_field );
            $instance['BTN'] = $options['widget']['BTN'];
            // Set BTH based on default height for chosen button
            $instance['BTH'] = $GLOBALS['svg_button_heights'][$instance['BTN']];
            $instance['BTC'] = $options['BTC'];

            $widget_options = get_option('widget_tibdit_widget');

            bd_log( "widget() dump args: " . var_export($args, true));
            bd_log( "widget() dump instance: " . var_export($instance, true));
            bd_log( "widget() dump options: " . var_export($options, true));

            $instance = $this->map_new_settings_names($instance, $this->settings_map);
            bd_log("widget() mapped instance: " . var_export($instance, true));

            extract( $instance );

            $plugurl = plugin_dir_url( __FILE__ );

            $hex = $instance['colour'];
            list($r, $g, $b)= sscanf($hex, "#%02x%02x%02x");
            $rgba = "rgba($r,$g,$b,0.1)";

            echo $args['before_widget']."<div class='bd widget' style='background-color: $rgba;'>";

            if (!$title) $title = "tibit";
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];

            if ($intro)
                echo "<p class='wp_widget_plugin_textarea'>$intro</p>";

            echo tib_button( $instance );

//            bd_log("widget() @$PAD #$subref %$count");

            echo "</div>".$args['after_widget'];
        }


        function generate_SUB(){
            if(in_the_loop()){
                return "WP_ID_".get_the_ID();
            }
            else{
                return "WP_SITE";
            }
        }



        function tib_shortcode_func( $atts, $content=null, $shortcode_name){

            if($atts){
                $atts = array_change_key_case($atts, CASE_UPPER);
            }

            $shortcode_atts = $atts;


            $html="";
            $options= get_option('tibdit_options');
            $base_option_values = array
            (
                'title' => "",
                'intro' => "",
                'BTN' => $options['BTN'],
                'PAD' => $options['PAD'],
                'SUB' => $this->generate_SUB(),
                'BTC' => $options['BTC'],
                'BTH' => $options['BTH'],
                'CBK' => '',
                'TIB' => '',
                'DUR' => $options['DUR'],
                'ASN' => $options['ASN']
            );
            $shortcode_option_values = array(
                'BTN' => $options['shortcode']['BTN'],
                'BTH' => $options['shortcode']['BTH'],
            );
            // wp_parse_args allows us to merge two arrays, with values (if specified) in the first array provided
            // taking precedence over values in the second array (defaulting to the second array)
            // We use our get_button_params() helper function to remove any irrelevant values, or empty string values
            $option_values = wp_parse_args(get_button_params($shortcode_option_values),
                $base_option_values);
            $instance = shortcode_atts( $option_values, $atts, "tib");

            bd_log( "tib_post_func() dump atts: " . var_export($atts, true));
            bd_log( "tib_post_func() dgit diff commit_id HEADump instance: " . var_export($instance, true));

            if($shortcode_name == "tib_inline"){
                $appearance= array('readmore' => true);

                if (is_null($content) or $content=="")  // no paired closing shortcode
                {
                    if (!isset($_COOKIE["tibbed_$instance[SUB]"])) // not already tibbed
                    {
                        bd_log( "tib_inline_func() inline #$instance[SUB] NULL content [[$content]]". var_export($content, true));
                        $html = "<a class='bd-link bd-live' onclick=\"bd_plugin_tib('$instance[PAD]','$instance[SUB]')\"> $instance[text] </a>";
                    }
                    else
                    {
                        bd_log( "tib_inline_func() inline #$instance[SUB] TIBBED NULL content [[$content]]". var_export($content, true));
                        $html = "";
                    }
                }
                else // enclosed content
                {
                    if (!isset($_COOKIE["tibbed_$instance[SUB]"])) // not already tibbed
                    {
                        bd_log( "tib_inline_func() inline #$instance[SUB] LINK content [[$content]]". var_export($content, true));
                        $html = "<a class='bd-link bd-live' onclick=\"bd_plugin_tib('$instance[PAD]','$instance[SUB]')\">$content</a>";
                    }
                    else
                    {
                        bd_log( "tib_inline_func() inline #$instance[SUB] TIBBED LINK content [[$content]]". var_export($content, true));
                        $html = "<span class='bd-link tibbed'>$content</span>";
                    }
                }

            }
            elseif ($content && $content !== ""){
                $appearance= array('readmore' => true);
                $html.=tib_button( $instance, $shortcode_atts, $appearance);
            }
            else{
                $html.=tib_button( $instance, $shortcode_atts);
            }




            return $html;

        }


        function tibdit_plugin_enqueue()
        {
            $plugurl = plugin_dir_url( __FILE__ );
            bd_log("tibdit_plugin_enqueue() ". $plugurl);

            bd_register_style( 'tibbee');
            wp_enqueue_style( 'bd-tibbee');

            bd_register_script( 'tib-functions-bottom', true, array('bd-tib-functions'));
            bd_register_script( 'tib-functions', false);

            bd_log("BD-TIB-ENQ");
            wp_enqueue_script( 'bd-tib-functions');
        }

    }
    add_action( 'widgets_init', 'register_tibdit_widget');
}

function register_tibdit_widget()
{
    bd_log("register_tibdit_widget() ");
    register_widget('tibditWidget');
}

function tib_buttons_init(){
    /* Injects initialisation script for tib buttons */
    $bd_options = get_option("tibdit_options");

    $html = '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/script.js/2.5.8/script.min.js"></script>';
    $html .= '<script>
    $script("https://widget.tibit.com/assets/js/tib-2.4.js", function(){ tibInit( {';
    if(isset($bd_options['tib_mode'])) {
        if($bd_options['tib_mode'] == 'PAD'){
            if(!$bd_options['PAD']){
                $html .= '
                "PAD": "mytibs9YhLYtrVhQkmTdbDS51H54WyrxTx",
                ';
            }
            else {
                $html .= '
                "PAD": "' . $bd_options['PAD'] . '",
                ';
            }
        }
        if ($bd_options['tib_mode'] == 'ASN') {
            $html .= '
            "ASN": "' . $bd_options['ASN'] . '",
            "TIB": "' . 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '",
        ';
        }
    }
    else{
        $html.= '
            "PAD": "'. $bd_options['PAD'] . '",
        ';
    }

    $html.='
        "BTN": "'. $bd_options['BTN'] . '",
        "DUR": "'. $bd_options['DUR'] . '",
        "CBK": "'. plugin_dir_url( __FILE__ ) . 'tibcbk.php",
    ';
    $html .= '});});</script>';
    echo $html;
}
add_action('wp_head', 'tib_buttons_init' );


function tib_button( $instance, $shortcode_atts=NULL, $appearance=NULL)
{
    $bd_options = get_option("tibdit_options");
    // Return if PAD or ASN don't a value (defaults to PAD='', which will evaluate to false here)
    if(!$bd_options['PAD'] && !$bd_options['ASN']){
        if(!isset($instance['PAD']) && !isset($instance['ASN'])){
            $instance['PAD'] = 'mytibs9YhLYtrVhQkmTdbDS51H54WyrxTx';
        }
    }

    if(!isset($instance['BTH'])){
        $instance['BTH'] = $GLOBALS['svg_button_heights'][$instance['BTN']];
    }

    global $image_resource_url;
    $html= "";

    $plugurl = plugin_dir_url( __FILE__ );

    include_once("button-factory/ButtonFactory.php");

    $mybutton = ButtonFactory::make_button( $instance, $shortcode_atts);

//    if ($appearance['readmore'])
//        $html.="<span class='annotation'>read more</span>";


    $html.= $mybutton->render();

    bd_log("plugindir: .$plugurl");

    return $html;

}

// Function added to the_content filter - executes the [tib] shortcode and appends it to the post's content
function insert_after_post($content){
    // Grab our options array from wordpress backend

    $options = get_option('tibdit_options');



    // Get sitewide button params from options
    $instance = get_button_params($options);
    // Get post-specific params from options
    $post_button_options = get_button_params($options['post']);
    // Override sitewide params with post-specific params
    $instance = wp_parse_args($post_button_options, $instance);
    // Generate button using our per-post settings
    $instance['SUB'] = $GLOBALS['wp_query']->post->post_name;
    $button = tib_button($instance);

    // If tib_button returns null, we just want to exit and return the content
    if($button === null){
        return $content;
    }
    // If the user has specified to only show buttons on single posts, check if we're on a single post page and return
    // the content unmodified if so
    if(isset($options['append_only_on_single']) && $options['append_only_on_single'] == "true" && is_single() === false){
        return $content;
    }
    $button = '<div class="bd-flex-item">' . $button . '</div>';
    $caption = $options['caption'];
    $caption = '<div class="bd-flex-item bd-side-text" style="">' . $caption . '</div>';
    $button_output = '<div class="bd-flex">' . $button . $caption . '</div>';


//  Check that the post_type property of the global post object is a post (we don't want to add this to pages)
    if($GLOBALS['post']->post_type === 'post') {

        if($options['append_before_content'] === 'true'){
            $content = $button_output . $content;
        }
        if($options['append_after_content'] === 'true'){
            $content = $content . $button_output;
        }

    }
//  Pass the content back to the filter, modified or not
    return $content;
}


function bd_log($message)
{
    $env = getenv("ENV");

    if ($env == 'dev' || $env == 'stage') {
        error_log(date("d H:i:s", time()) . " - " . $message . "\n", 3, plugin_dir_path(__FILE__) . 'tibdit.log');
    }
}

?>