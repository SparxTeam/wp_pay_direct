<?php
//<!-- This file is used to markup the administration form of the plugin. -->

class WpPayDirectAdmin {
	
/*-------------------------------------------*
	 * 		Page handler (menu) functions
	 *-------------------------------------------*/
	
	function wp_pay_direct_settings_page( $args ) {
	
		// Update
		if( isset( $_POST['updateoption'] ) ){
				
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
		
		/*--------------------------------------------*
		 *  WP Pay Direct Form Builder Page
		 *---------------------------------------------*/
		
		function wp_pay_direct_form_page( $args ) {
			// Settings page method
			?>
					<div class="wrap">
					<?php screen_icon('wp-pay-direct'); ?>
					<h2>WP Pay Direct Custom Form Fields</h2>
					
				
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
				                      <?php  echo get_option( 'wp_pay_direct_form_builder' ); ?>
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
	
}
global $wppd_admin;
$wppd_admin = new WpPayDirectAdmin();
