<?php
/*
Plugin Name: WP Pay Direct
Plugin URI: http://www.csschopper.com/
Description: WP Pay Direct is a payment solution for WP based websites. It provides a 'direct pay' interface for visitors/buyers of your business website.
Version: 1.0.0
Author: WP team at Sparx IT Solution Pvt Ltd
Author URI: http://www.csschopper.com/
Author Email: sanjay.kumar@sparxtechnologies.com
License:

  Copyright 2013 Sparx IT Solutions pvt ltd. (sales@csschopper.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

/**
 * Base Class of the plugin 
 * 
 * Based on the WP Plugin Dev framework.
 * For more information:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate
 * 
 */
if (!defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

if (!defined('DIRECTORY_SEPARATOR')) {
    if (strpos(php_uname('s'), 'Win') !== false)
        define('DIRECTORY_SEPARATOR', '\\');
    else
        define('DIRECTORY_SEPARATOR', '/');
}
$pluginPath = ABSPATH . PLUGINDIR . DIRECTORY_SEPARATOR . "wp-pay-direct";
define('WPPD_PATH', $pluginPath);
$viewsPath = $pluginPath . DIRECTORY_SEPARATOR. 'views'.DIRECTORY_SEPARATOR;
define('WPPD_VIEWS_PATH', $viewsPath);
$asolutePath = dirname(__FILE__) . DIRECTORY_SEPARATOR;
define('WPPD_ABSPATH', $asolutePath);


require_once(WPPD_VIEWS_PATH.'admin.php');
require_once(WPPD_VIEWS_PATH.'display.php');
require_once(WPPD_VIEWS_PATH.'shortcode.php');
require_once(WPPD_VIEWS_PATH.'wp_pay_direct_db.php');

class WpPayDirect {
	
	/*--------------------------------------------*
	 * Constants
	*--------------------------------------------*/
	const name = 'WP Pay Direct';
	const slug = 'wp-pay-direct';
	 
	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/
	
	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {
		global $wppd_shortcode; // object of WpPayDirectShortcode class /views/shortcode.php
		/**
		 * 	Load plugin text domain
		 *  This is a translation/multilingual bit. So commented out for now. Will develop in the second phase of plugin
		 */
		//add_action( 'init', array( $this, 'plugin_textdomain' ) );

		// Register admin styles and scripts
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
	
		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
	
		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		register_uninstall_hook( __FILE__, array( 'WpPayDirect', 'uninstall' ) );
		
	  
		/**
		 *	Register the shortcode [WP_PAY_DIRECT]
		 *
		 *	For more information:
		 *  Reference: http://codex.wordpress.org/Function_Reference/add_shortcode#Examples
		 */
		
		add_shortcode('WP_PAY_DIRECT', array( &$wppd_shortcode, 'render_wp_pay_direct_shortcode' ) );
		
		/**
		 * 	Admin interface: Menu for the plugin in admin
		 */
		add_action( 'admin_menu', array( &$this, 'create_wp_pay_direct_admin_menu' ) );
		
		/**
		 * 	Ajax request to save "form" in DB
		 * 
		 */
		add_action('wp_ajax_wppd_update_form', array( $this, 'wppd_update_form' ) );
		add_action('wp_ajax_nopriv_wppd_update_form', array( $this, 'wppd_update_form_error' ) );
		
		/**
		 * 	Ajax request to load "form" from DB
		 * 
		 */
		add_action('wp_ajax_wppd_load_form', array( $this, 'wppd_load_form' ) );
		add_action('wp_ajax_nopriv_wppd_load_form', array( $this, 'wppd_update_form_error' ) );
	    

	} // end constructor
	
	/**
	 * Fired when the plugin is activated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function activate( $network_wide ) {
		
		// Plugin options
		$this->wp_pay_direct_create_options();
		
		// payment log table
		
		global $wp_pay_direct_db;		
		$wp_pay_direct_db->create_wppd_db_table();
		
	} // end activate
	
	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function deactivate( $network_wide ) {
		// TODO:	Define deactivation functionality here		
	} // end deactivate
	
	/**
	 * Fired when the plugin is uninstalled.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function uninstall( $network_wide ) {
		// TODO:	Define uninstall functionality here		
	} // end uninstall

	/**
	 * Loads the plugin text domain for translation
	 */
	public function plugin_textdomain() {
	
		// TODO: replace "plugin-name-locale" with a unique value for your plugin
		$domain = 'plugin-name-locale';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
        load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
        load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	} // end plugin_textdomain

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {
	
		// Admin interface style
		wp_enqueue_style( 'wp-pay-direct-admin-styles', plugins_url( 'wp-pay-direct/css/admin.css' ) );
		
		// Form builder 
		wp_enqueue_style( 'bootstrap-style', plugins_url( 'wp-pay-direct/form-builder/css/bootstrap.css' ) );
		wp_enqueue_style( 'bootstrap-responsive-style', plugins_url( 'wp-pay-direct/form-builder/css/bootstrap-responsive.css' ) );
	
	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */	
	public function register_admin_scripts() {
	
		// Scripts used for admin interface
		
		// Form builder includes
		wp_enqueue_script( 'bootstrap-js', plugins_url( 'wp-pay-direct/form-builder/js/bootstrap.js' ) );
		wp_enqueue_script( 'bootstrap-tab-js', plugins_url( 'wp-pay-direct/form-builder/js/bootstrap-tab.js' ) );
		
		// Form builder call
		wp_enqueue_script( 'fb-js', plugins_url( 'wp-pay-direct/js/fb.js' ) );
		
		// Custom script file
		wp_enqueue_script( 'wp-pay-direct-admin-script', plugins_url( 'wp-pay-direct/js/admin.js' ) );
		
		// Localize 
		$params = array( 
						'ajaxurl' => admin_url( 'admin-ajax.php' ), 
						'template_url' => get_bloginfo('template_directory') 
				);
		wp_localize_script( 'wp-pay-direct-admin-script', 'wppd_ajax', $params );
	
	} // end register_admin_scripts
	
	/**
	 * Registers and enqueues plugin-specific styles.
	 */
	public function register_plugin_styles() {
	
		wp_enqueue_style( 'wp-pay-direct-plugin-styles', plugins_url( 'wp-pay-direct/css/display.css' ) );
	
	} // end register_plugin_styles
	
	/**
	 * Registers and enqueues plugin-specific scripts.
	 */
	public function register_plugin_scripts() {
	
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'wp-pay-direct-plugin-script', plugins_url( 'wp-pay-direct/js/display.js' ) );
		
		// Localize
		$params = array(
				'loader_url' => plugins_url( 'wp-pay-direct/images/icons/ajax-loader.gif' )
		);
		wp_localize_script( 'wp-pay-direct-plugin-script', 'wppd_display', $params );
	
	} // end register_plugin_scripts
	
	
	/*--------------------------------------------*
	 * Core Functions: Actions
	 *---------------------------------------------*/
	
	/**
 	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *		  WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *		  Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 */
	
	function create_wp_pay_direct_admin_menu() {
		global $wppd_admin; // object of WpPayDirectAdmin class /views/admin.php
		/**
		 * 	Adding 'Admin Menu' : add_menu_page
		 *
		 * 	For more information:
		 * 	http://codex.wordpress.org/Function_Reference/add_menu_page
		 * 
		 * 	Menu Icon: Generated from http://iconizer.net/
		 */
		add_menu_page( 'WP Pay Direct', 'Pay Direct', 'manage_options', __FILE__, array( &$wppd_admin, 'wp_pay_direct_list_pyments' ), plugins_url( 'images/icons/direct-pay-icon.png', __FILE__ ) );
		
				
		//create submenu items
		add_submenu_page( __FILE__, 'Payment List', 'Payments', 'manage_options',__FILE__, array( &$wppd_admin, 'wp_pay_direct_list_pyments' ) );
		add_submenu_page( __FILE__, 'Settings', 'Settings', 'manage_options','wp_pay_direct_settings', array( &$wppd_admin, 'wp_pay_direct_settings_page' ) );
		add_submenu_page( __FILE__, 'Form Builder', 'Optional Form Fields', 'manage_options','wp_pay_direct_form_builder', array( &$wppd_admin, 'wp_pay_direct_form_page' ) );
		
			
	
	}
	
	/**
	 * 	Admin 'Form Builder' update
	 * 	
	 * Ajax Handler function
	 * action: wppd_update_form
	 * 	
	 * @params Request array of AJAX call
	 * @returns boolean 
	 * 
	 */
	
	function wppd_update_form(){
		
		if( isset( $_REQUEST ) ){
			// Form Source To Be Used On Front End
			$form_source	=	stripslashes( $_REQUEST[ 'form_source' ] );
			
			// Updates form
			$isUpdate	=	update_option( 'wp_pay_direct_form', $form_source );
			
			//Form builder source to regenerate form fields in admin form build area
			$form_builder_source  =  stripslashes( $_REQUEST[ 'form_builder_source' ] );
				
			// Updates form source
			$formBuildertUpdate  =  update_option( 'wp_pay_direct_form_builder', $form_builder_source );
			if( $isUpdate &&  $formBuildertUpdate){
				echo "true";
			} else {
				echo "false";
			}
			
		}
		die();
	}
	
	/**
	 * 	Loads "Form Builder" form
	 * 	
	 * 	@return String (HTML form)
	 * 
	 */
	
	function wppd_load_form(){
		
		if( isset( $_REQUEST ) ){
			$wp_pay_direct_form		=	get_option( 'wp_pay_direct_form' );
			echo $wp_pay_direct_form;
		}
		die();
	}
	/**
	 * 	"nopriv" handler function for,
	 * 	action : wppd_update_form
	 * 
	 * 	@returns Error for non logged in users
	 * 
	 */
	
	function wppd_update_form_error(){
		
		wp_die( 'You\'re attempting to get Unauthorized Access!' );
		
	}
	
	 /*--------------------------------------------*
	  * Core Functions: Filters
	 *---------------------------------------------*/
	
	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *		  WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *		  Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 */
		
	
	/*-----------------------------------*
	 * 	Helper function
	 *------------------------------------*/
	 
	// Plugin options : will call on activation hook	
	function wp_pay_direct_create_options() {
			
			//	Settings page
			add_option( 'wp_pay_direct_options', array(	'wp_pay_direct_user_defined_name' => '',
														'wp_pay_direct_business' => '',
														'wp_pay_direct_pay_mod' => 'https://www.sandbox.paypal.com/cgi-bin/webscr')
						);
			// Form
			add_option( 'wp_pay_direct_form', '');
			
			// Form Builder
		     add_option('wp_pay_direct_form_builder','<div id="legend" class="component" rel="popover" title="Form Title" trigger="manual" data-content="<form class=\'form\'><div class=\'controls\'>   <label class=\'control-label\'>Title</label><input class=\'input-large\' type=\'text\' name=\'title\' id=\'text\'><hr/><button class=\'btn btn-info\'>Save</button><button class=\'btn btn-danger\'>Cancel</button></div></form>"><legend class="valtype" data-valtype="text">Form Name</legend></div>');
	}
  
} // end class

// Initialize the plugin: Invoking the class
$wp_pay_direct = new WpPayDirect();
