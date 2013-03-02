<?php
/*
Plugin Name: WP Pay Direct
Plugin URI: http://www.csschopper.com/
Description: WP Pay Direct is a payment solution for WP based websites. It provides a 'direct pay' interface for visitors/buyers of your business website.
Version: 1.0.0
Author: Deepak Tripathi & WP team at Sparx IT Solution Pvt Ltd
Author URI: http://www.csschopper.com/
Author Email: deepak@sparxtechnologies.com
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
		
		add_shortcode('WP_PAY_DIRECT', array( 'WpPayDirect', 'render_wp_pay_direct_shortcode' ) );
		
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
	
		// TODO:	Change 'plugin-name' to the name of your plugin
		wp_enqueue_style( 'wp-pay-direct-plugin-styles', plugins_url( 'wp-pay-direct/css/display.css' ) );
	
	} // end register_plugin_styles
	
	/**
	 * Registers and enqueues plugin-specific scripts.
	 */
	public function register_plugin_scripts() {
	
		// TODO:	Change 'plugin-name' to the name of your plugin
		wp_enqueue_script( 'wp-pay-direct-plugin-script', plugins_url( 'wp-pay-direct/js/display.js' ) );
	
	} // end register_plugin_scripts
	
	
	/*--------------------------------------------*
	 * Plugin's Short code handler 
	 *---------------------------------------------*/
	
	function render_wp_pay_direct_shortcode($atts) {
	
		// Extract the attributes
		extract(shortcode_atts(array(
				'attr1' => 'foo', //foo is a default value
				'attr2' => 'bar'
		), $atts));
		// you can now access the attribute values using $attr1 and $attr2
	   return get_option('wp_pay_direct_form');
	}
	
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
	
		/**
		 * 	Adding 'Admin Menu' : add_menu_page
		 *
		 * 	For more information:
		 * 	http://codex.wordpress.org/Function_Reference/add_menu_page
		 * 
		 * 	Menu Icon: Generated from http://iconizer.net/
		 */
		add_menu_page( 'WP Pay Direct', 'Pay Direct', 'manage_options', __FILE__, array( &$this, 'wp_pay_direct_form_page' ), plugins_url( 'images/icons/direct-pay-icon.png', __FILE__ ) );
		
				
		//create submenu items
		add_submenu_page( __FILE__, 'Form Builder', 'Form Builder', 'manage_options',__FILE__, array( &$this, 'wp_pay_direct_form_page' ) );
		add_submenu_page( __FILE__, 'Settings', 'Settings', 'manage_options','wp_pay_direct_settings', array( &$this, 'wp_pay_direct_settings_page' ) );
		
			
	
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
			
			$form_source	=	stripslashes( $_REQUEST[ 'form_source' ] );
			
			// Updates form
			$isUpdate	=	update_option( 'wp_pay_direct_form', $form_source );
			if( $isUpdate ){
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
	}
	
	
	/*-------------------------------------------*
	 * 		Page handler (menu) functions
	 *-------------------------------------------*/
	
	function wp_pay_direct_settings_page( $args ) {
	
		// Update
		if( isset($_POST['updateoption']) ){
				
			$paypal_name 	 = 	$_POST[ 'wp_pay_direct_user_defined_name' ];
			$paypal_business = 	$_POST[ 'wp_pay_direct_business' ];
			$paypal_mode 	 = 	$_POST[ 'wp_pay_direct_pay_mod' ];
				
			$get_options = array( 'wp_pay_direct_user_defined_name' => $paypal_name,
					'wp_pay_direct_business' => $paypal_business,
					'wp_pay_direct_pay_mod' => $paypal_mode
			);
				
			update_option( 'wp_pay_direct_options', $get_options );
				
		}
	
	
	
		// Settings
		$wppd_options = get_option( 'wp_pay_direct_options' );
	
		$wp_pay_direct_user_defined_name	=	$wppd_options[ 'wp_pay_direct_user_defined_name' ]  ? $wppd_options[ 'wp_pay_direct_user_defined_name' ] : '';
		$wp_pay_direct_business				=	$wppd_options[ 'wp_pay_direct_business' ]  			? $wppd_options[ 'wp_pay_direct_business' ] 		 : '';
		$wp_pay_direct_pay_mod				=	$wppd_options[ 'wp_pay_direct_pay_mod' ] 			? $wppd_options[ 'wp_pay_direct_pay_mod' ] 			 : '';
	
		?>
				<div class="wrap">
				<?php screen_icon('wp-pay-direct'); ?>
				<h2>WP Pay Direct Settings</h2>
				<div class="postbox">
				
					<h3 class="wpdp_box_header">PayPal Payments Standard 2.0 Settings</h3>
					<div class="inside">
						<form action="" method="post">
							<table class="form-table">
								<tbody>
									<tr>
							           <td>Display Name</td>
										<td>
											<input type="text" name="wp_pay_direct_user_defined_name" value="<?php echo $wp_pay_direct_user_defined_name; ?>"><br>
											<small>The text that people see when making a purchase.</small>
										</td>
									</tr>
					
								  <tr>
								      <td>Username:</td><!-- sanjay_1336725211_biz@sparxtechnologies.com -->
								      <td>
								      		<input type="text" size="40" name="wp_pay_direct_business" value="<?php echo $wp_pay_direct_business;?>" >
								      </td>
								  </tr>
								  <tr>
								  	<td></td>
								  	<td colspan="1">
									  	<span class="wpscsmall description">
									  		This is your PayPal email address.
									  	</span>
								  	</td>
								  </tr>
		
		  						<tr>
								      <td>Account Type:</td>
								      <td>
										<select name="wp_pay_direct_pay_mod">
											<option value="https://www.paypal.com/cgi-bin/webscr" <?php if( $wp_pay_direct_pay_mod === 'https://www.paypal.com/cgi-bin/webscr' ) {?> selected="selected" <?php } ?>>Live Account</option>
											<option value="https://www.sandbox.paypal.com/cgi-bin/webscr" <?php if( $wp_pay_direct_pay_mod === 'https://www.sandbox.paypal.com/cgi-bin/webscr' ) {?> selected="selected" <?php } ?> >Sandbox Account</option>
										</select>
									  </td>
								</tr>
								  <tr>
									 <td colspan="1">
									 </td>
									 <td>
										<span class="wpscsmall description">
								  			If you have a PayPal developers Sandbox account please use Sandbox mode, if you just have a standard PayPal account then you will want to use Live mode.
								  		</span>
								  	  </td>
								  </tr>
			   					<tr class="update_gateway">
									<td colspan="2">
										<div class="submit">
											<input type="submit" value="Update »" name="updateoption">
										</div>
									</td>
								</tr>
			  
		  					</tbody>
		  				</table>
	  				</form>
				</div>
			</div>
		</div>
		<?php
						
		}
		
		function wp_pay_direct_form_page( $args ) {
			// Settings page method
			?>
					<div class="wrap">
					<?php screen_icon('wp-pay-direct'); ?>
					<h2>WP Pay Direct Form Builder</h2>
					
				
	    <div class="container">
				      <div class="row clearfix">
				        <div class="span6">
				          <div class="clearfix">
				             <div class="update-form-up">
	          	 				<input class='button-primary pay-sub' type='submit' name="update_pay_direct_form" value="<?php _e('Update Form'); ?>" />
	          	 				<small class="wppd_load wppd_hide"></small>
	          				 </div>
				            <hr>
				            <div id="build">
				              	<form id="target" class="form-horizontal">
				                  <div id="legend" class="component" rel="popover" title="Form Title" trigger="manual"
				                    data-content="
				                    <form class='form'>
				                      <div class='controls'>
				                        <label class='control-label'>Title</label> <input class='input-large' type='text' name='title' id='text'>
				                        <hr/>
				                        <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
				                      </div>
				                    </form>">
				                    <legend class="valtype" data-valtype="text">Form Name</legend>
				                  </div>
				                  <?php  echo get_option( 'wp_pay_direct_form' ); ?>
				              	</form>
				            </div>
				          </div>
				        </div>
						
						
	          	
	        <div class="span6">
	            <h2>Drag & Drop components</h2>
	            <hr>
					          <div class="tabbable">
					            <ul class="nav nav-tabs" id="navtab">
					              <li class="active"><a href="#1" data-toggle="tab">Input</a></li>
					              <li class><a href="#2" data-toggle="tab">Select</a></li>
					              <li class><a href="#3" data-toggle="tab">Checkbox / Radio</a></li>
					              <li class><a href="#4" data-toggle="tab">File / Button</a></li>
					              <li class><a id="sourcetab" href="#5" data-toggle="tab">Generated Source</a></li>
					            </ul>
					            <form class="form-horizontal" id="components">
					              <fieldset>
					                <div class="tab-content">
					
					                  <div class="tab-pane active" id="1">
					
					                    <div class="control-group component" data-type="text" rel="popover" title="Text Input" trigger="manual"
					                      data-content="
					                      <form class='form'>
					                        <div class='controls'>
					                          <label class='control-label'>Label Text</label> <input class='input-large' type='text' name='label' id='label'>
					                          <label class='control-label'>Placeholder</label> <input type='text' name='placeholder' id='placeholder'>
					                          <label class='control-label'>Help Text</label> <input type='text' name='help' id='help'>
					                          <hr/>
					                          <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
					                        </div>
					                      </form>"
					                      >
					
					                      <!-- Text input-->
					                      <label class="control-label valtype" for="input01" data-valtype='label'>Text input</label>
					                      <div class="controls">
					                        <input type="text" placeholder="placeholder" class="input-xlarge valtype" data-valtype="placeholder" >
					                        <p class="help-block valtype" data-valtype='help'>Supporting help text</p>
					                      </div>
					           </div>
	
	
	                    <div class="control-group component" data-type="search" rel="popover" title="Search Input" trigger="manual"
	                      data-content="
	                      <form class='form'>
	                        <div class='controls'>
	                          <label class='control-label'>Label Text</label> <input class='input-large' type='text' name='label' id='label'>
	                          <label class='control-label'>Placeholder</label> <input type='text' name='placeholder' id='placeholder'>
	                          <label class='control-label'>Help Text</label> <input type='text' name='help' id='help'>
	                          <hr/>
	                          <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
	                        </div>
	                      </form>"
	                      >
	
	                      <!-- Search input-->
	                      <label class="control-label valtype" data-valtype="label">Search input</label>
	                      <div class="controls">
	                        <input type="text" placeholder="placeholder" class="input-xlarge search-query valtype" data-valtype="placeholder">
	                        <p class="help-block valtype" data-valtype="help">Supporting help text</p>
	                      </div>
	
	                    </div>
	
	
	                    <div class="control-group component" data-type="prep-text" rel="popover" title="Prepended Text Input" trigger="manual"
	                      data-content="
	                      <form class='form'>
	                        <div class='controls'>
	                          <label class='control-label'>Label Text</label> <input class='input-large' type='text' name='label' id='label'>
	                          <label class='control-label'>Prepend</label> <input type='text' name='prepend' id='prepend'>
	                          <label class='control-label'>Placeholder</label> <input type='text' name='placeholder' id='placeholder'>
	                          <label class='control-label'>Help Text</label> <input type='text' name='help' id='help'>
	                          <hr/>
	                          <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
	                        </div>
	                      </form>"
	                      >
	
	                      <!-- Prepended text-->
	                      <label class="control-label valtype" data-valtype="label">Prepended text</label>
	                      <div class="controls">
	                        <div class="input-prepend">
	                          <span class="add-on valtype" data-valtype="prepend">^_^</span>
	                          <input class="span2 valtype" placeholder="placeholder" id="prependedInput" type="text" data-valtype="placeholder">
	                        </div>
	                        <p class="help-block valtype" data-valtype="help">Supporting help text</p>
	                      </div>
	
	                    </div>
	
	                    <div class="control-group component" data-type="app-text" rel="popover" title="Appended Text Input" trigger="manual"
	                      data-content="
	                      <form class='form'>
	                        <div class='controls'>
	                          <label class='control-label'>Label Text</label> <input class='input-large' type='text' name='label' id='label'>
	                          <label class='control-label'>Appepend</label> <input type='text' name='append' id='append'>
	                          <label class='control-label'>Placeholder</label> <input type='text' name='placeholder' id='placeholder'>
	                          <label class='control-label'>Help Text</label> <input type='text' name='help' id='help'>
	                          <hr/>
	                          <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
	                        </div>
	                      </form>"
	                      >
	
	                      <!-- Appended input-->
	                      <label class="control-label valtype" data-valtype="label">Appended text</label>
	                      <div class="controls">
	                        <div class="input-append">
	                          <input class="span2 valtype" data-valtype="placeholder" placeholder="placeholder" type="text">
	                          <span class="add-on valtype" data-valtype="append">^_^</span>
	                        </div>
	                        <p class="help-block valtype" data-valtype="help">Supporting help text</p>
	                      </div>
	
	                    </div>
	
	                    <div class="control-group component" rel="popover" title="Search Input" trigger="manual"
	                      data-content="
	                      <form class='form'>
	                        <div class='controls'>
	                          <label class='control-label'>Label Text</label> <input class='input-large' type='text' name='label' id='label'>
	                          <label class='control-label'>Placeholder</label> <input type='text' name='placeholder' id='placeholder'>
	                          <label class='control-label'>Help Text</label> <input type='text' name='help' id='help'>
	                          <label class='checkbox'><input type='checkbox' class='input-inline' name='checked' id='checkbox'>Checked</label>
	                          <hr/>
	                          <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
	                        </div>
	                      </form>"
	                      >
	
	                      <!-- Prepended checkbox -->
	                      <label class="control-label valtype" data-valtype="label">Prepended checkbox</label>
	                      <div class="controls">
	                        <div class="input-prepend">
	                          <span class="add-on">
	                            <label class="checkbox">
	                              <input type="checkbox" class="valtype" data-valtype="checkbox">
	                            </label>
	                          </span>
	                          <input class="span2 valtype" placeholder="placeholder" type="text" data-valtype="placeholder">
	                        </div>
	                        <p class="help-block valtype" data-valtype="help">Supporting help text</p>
	                      </div>
	
	                    </div>
	
	                    <div class="control-group component" rel="popover" title="Search Input" trigger="manual"
	                      data-content="
	                      <form class='form'>
	                        <div class='controls'>
	                          <label class='control-label'>Label Text</label> <input class='input-large' type='text' name='label' id='label'>
	                          <label class='control-label'>Placeholder</label> <input type='text' name='placeholder' id='placeholder'>
	                          <label class='control-label'>Help Text</label> <input type='text' name='help' id='help'>
	                          <label class='checkbox'><input type='checkbox' class='input-inline' name='checked' id='checkbox'>Checked</label>
	                          <hr/>
	                          <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
	                        </div>
	                      </form>"
	                      >
	
	                      <!-- Appended checkbox -->
	                      <label class="control-label valtype" data-valtype="label">Append checkbox</label>
	                      <div class="controls">
	                        <div class="input-append">
	                          <input class="span2 valtype" placeholder="placeholder" type="text" data-valtype="placeholder">
	                          <span class="add-on">
	                            <label class="checkbox" for="appendedCheckbox">
	                              <input type="checkbox" class="valtype" data-valtype="checkbox">
	                            </label>
	                          </span>
	                        </div>
	                        <p class="help-block valtype" data-valtype="help">Supporting help text</p>
	                      </div>
	                    </div>
	
	                    <div class="control-group component" rel="popover" title="Search Input" trigger="manual"
	                      data-content="
	                      <form class='form'>
	                        <div class='controls'>
	                          <label class='control-label'>Label Text</label> <input class='input-large' type='text' name='label' id='label'>
	                          <hr/>
	                          <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
	                        </div>
	                      </form>"
	                      >
	
	                      <!-- Textarea -->
	                      <label class="control-label valtype" data-valtype="label">Textarea</label>
	                      <div class="controls">
	                        <div class="textarea">
	                              <textarea type="" class="valtype" data-valtype="checkbox" /> </textarea>
	                        </div>
	                      </div>
	                    </div>
	
	                  </div>
						
	                  <div class="tab-pane" id="2">
	
	                    <div class="control-group component" rel="popover" title="Search Input" trigger="manual"
	                      data-content="
	                      <form class='form'>
	                        <div class='controls'>
	                          <label class='control-label'>Label Text</label> <input class='input-large' type='text' name='label' id='label'>
	                          <label class='control-label'>Options: </label>
	                          <textarea style='min-height: 200px' id='option'> </textarea>
	                          <hr/>
	                          <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
	                        </div>
	                      </form>"
	                      >
	
	                      <!-- Select Basic -->
	                      <label class="control-label valtype" data-valtype="label">Select - Basic</label>
	                      <div class="controls">
	                        <select class="input-xlarge valtype" data-valtype="option">
	                          <option>Enter</option>
	                          <option>Your</option>
	                          <option>Options</option>
	                          <option>Here!</option>
	                        </select>
	                      </div>
	
	                    </div>
	
	                    <div class="control-group component" rel="popover" title="Search Input" trigger="manual"
	                      data-content="
	                      <form class='form'>
	                        <div class='controls'>
	                          <label class='control-label'>Label Text</label> <input class='input-large' type='text' name='label' id='label'>
	                          <label class='control-label'>Options: </label>
	                          <textarea style='min-height: 200px' id='option'></textarea>
	                          <hr/>
	                          <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
	                        </div>
	                      </form>"
	                      >
	
	                      <!-- Select Multiple -->
	                      <label class="control-label valtype" data-valtype="label">Select - Multiple</label>
	                      <div class="controls">
	                        <select class="input-xlarge valtype" multiple="multiple" data-valtype="option">
	                          <option>Enter</option>
	                          <option>Your</option>
	                          <option>Options</option>
	                          <option>Here!</option>
	                        </select>
	                      </div>
	                    </div>
	
	                  </div>
	
	                  <div class="tab-pane" id="3">
	
	                    <div class="control-group component" rel="popover" title="Multiple Checkboxes" trigger="manual"
	                      data-content="
	                      <form class='form'>
	                        <div class='controls'>
	                          <label class='control-label'>Label Text</label> <input class='input-large' type='text' name='label' id='label'>
	                          <label class='control-label'>Options: </label>
	                          <textarea style='min-height: 200px' id='checkboxes'> </textarea>
	                          <hr/>
	                          <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
	                        </div>
	                      </form>"
	                      >
	                      <label class="control-label valtype" data-valtype="label">Checkboxes</label>
	                      <div class="controls valtype" data-valtype="checkboxes">
	
	                        <!-- Multiple Checkboxes -->
	                        <label class="checkbox">
	                          <input type="checkbox" value="Option one">
	                          Option one
	                        </label>
	                        <label class="checkbox">
	                          <input type="checkbox" value="Option two">
	                          Option two
	                        </label>
	                      </div>
	
	                    </div>
	
	                    <div class="control-group component" rel="popover" title="Multiple Radios" trigger="manual"
	                      data-content="
	                      <form class='form'>
	                        <div class='controls'>
	                          <label class='control-label'>Label Text</label> <input class='input-large' type='text' name='label' id='label'>
	                          <label class='control-label'>Group Name Attribute</label> <input class='input-large' type='text' name='name' id='name'>
	                          <label class='control-label'>Options: </label>
	                          <textarea style='min-height: 200px' id='radios'></textarea>
	                          <hr/>
	                          <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
	                        </div>
	                      </form>"
	                      >
	                      <label class="control-label valtype" data-valtype="label">Radio buttons</label>
	                      <div class="controls valtype" data-valtype="radios">
	
	                        <!-- Multiple Radios -->
	                        <label class="radio">
	                          <input type="radio" value="Option one" name="group" checked="checked">
	                          Option one
	                        </label>
	                        <label class="radio">
	                          <input type="radio" value="Option two" name="group">
	                          Option two
	                        </label>
	                      </div>
	
	                    </div>
	
	                    <div class="control-group component" rel="popover" title="Inline Checkboxes" trigger="manual"
	                      data-content="
	                      <form class='form'>
	                        <div class='controls'>
	                          <label class='control-label'>Label Text</label> <input class='input-large' type='text' name='label' id='label'>
	                          <textarea style='min-height: 200px' id='inline-checkboxes'></textarea>
	                          <hr/>
	                          <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
	                        </div>
	                      </form>"
	                      >
	                      <label class="control-label valtype" data-valtype="label">Inline Checkboxes</label>
	
	                      <!-- Multiple Checkboxes -->
	                      <div class="controls valtype" data-valtype="inline-checkboxes">
	                        <label class="checkbox inline">
	                          <input type="checkbox" value="1"> 1
	                        </label>
	                        <label class="checkbox inline">
	                          <input type="checkbox" value="2"> 2
	                        </label>
	                        <label class="checkbox inline">
	                          <input type="checkbox" value="3"> 3
	                        </label>
	                      </div>
	
	                    </div>
	
	                    <div class="control-group component" rel="popover" title="Inline radioes" trigger="manual"
	                      data-content="
	                      <form class='form'>
	                        <div class='controls'>
	                          <label class='control-label'>Label Text</label> <input class='input-large' type='text' name='label' id='label'>
	                          <label class='control-label'>Group Name Attribute</label> <input class='input-large' type='text' name='name' id='name'>
	                          <textarea style='min-height: 200px' id='inline-radios'></textarea>
	                          <hr/>
	                          <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
	                        </div>
	                      </form>"
	                      >
	                      <label class="control-label valtype" data-valtype="label">Inline radios</label>
	                      <div class="controls valtype" data-valtype="inline-radios">
	
	                        <!-- Inline Radios -->
	                        <label class="radio inline">
	                          <input type="radio" value="1" checked="checked" name="group">
	                          1
	                        </label>
	                        <label class="radio inline">
	                          <input type="radio" value="2" name="group">
	                          2
	                        </label>
	                        <label class="radio inline">
	                          <input type="radio" value="3">
	                          3
	                        </label>
	                      </div>
	                    </div>
	
	                  </div>
	
	                  <div class="tab-pane" id="4">
	                    <div class="control-group component" rel="popover" title="File Upload" trigger="manual"
	                      data-content="
	                      <form class='form'>
	                        <div class='controls'>
	                          <label class='control-label'>Label Text</label> <input class='input-large' type='text' name='label' id='label'>
	                          <hr/>
	                          <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
	                        </div>
	                      </form>"
	                      >
	                      <label class="control-label valtype" data-valtype="label">File Button</label>
	
	                      <!-- File Upload -->
	                      <div class="controls">
	                        <input class="input-file" id="fileInput" type="file">
	                      </div>
	                    </div>
	
	                    <div class="control-group component" rel="popover" title="Search Input" trigger="manual"
	                      data-content="
	                      <form class='form'>
	                        <div class='controls'>
	                          <label class='control-label'>Label Text</label> <input class='input-large' type='text' name='label' id='label'>
	                          <label class='control-label'>Button Text</label> <input class='input-large' type='text' name='label' id='button'>
	                          <label class='control-label' id=''>Type: </label>
	                          <select class='input' id='color'>
	                            <option id='btn-default'>Default</option>
	                            <option id='btn-primary'>Primary</option>
	                            <option id='btn-info'>Info</option>
	                            <option id='btn-success'>Success</option>
	                            <option id='btn-warning'>Warning</option>
	                            <option id='btn-danger'>Danger</option>
	                            <option id='btn-inverse'>Inverse</option>
	                          </select>
	                          <hr/>
	                          <button class='btn btn-info'>Save</button><button class='btn btn-danger'>Cancel</button>
	                        </div>
	                      </form>"
	                      >
	                      <label class="control-label valtype" data-valtype="label">Button</label>
	
	                      <!-- Button -->
	                      <div class="controls valtype"  data-valtype="button">
	                        <button class='btn btn-success'>Button</button>
	                      </div>
	                    </div>
	                  </div>
	
	
	                  <div class="tab-pane" id="5">
	                    <textarea id="source" class="span6"></textarea>
	                  </div>
	                </fieldset>
	              </form>
	              
	            </div>
	            
	          </div> <!-- row -->
	          <div style="clear:both"></div>
	          <div class="update-form-bottom">
	          	 			<input class='button-primary pay-sub' type='submit' name="update_pay_direct_form" value="<?php _e('Update Form'); ?>" />
	          	 			<small class="wppd_load wppd_hide"></small>
	          			</div>
	        </div> <!-- /container -->
					</div>
					<?php
							
			}
  
} // end class

// Initialize the plugin: Invoking the class
$wp_pay_direct = new WpPayDirect();
