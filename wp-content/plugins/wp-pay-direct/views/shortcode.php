<?php
class WpPayDirectShortcode {
	
	/*--------------------------------------------*
	 * 	generating Secure Code for transacion
	 *--------------------------------------------*/
	function generateSecureCode(){
		
		list( $usec, $sec ) = explode( ' ', microtime() );
  		$seed = (float) $sec + ( (float) $usec * 100000 );
  		
  		srand( $seed );
		$randval = rand();
		
		return $randval;
  	
	}
	
	/*--------------------------------------------*
	 * Plugin's Short code handler 
	 *---------------------------------------------*/
	
	function render_wp_pay_direct_shortcode( $atts ) {
		
	/**
	  *  Get "Custom form fields" saved in DB
	  */
		
	  $wppd_custom_form = trim( get_option( 'wp_pay_direct_form' ) );
	  
	  $wp_pay_direct_options = get_option( 'wp_pay_direct_options' );
	  $wp_pay_direct_business_name = $wp_pay_direct_options[ 'wp_pay_direct_business' ];
	  $wppd_form_action 	= 	$wp_pay_direct_options[ 'wp_pay_direct_pay_mod' ];
	  
	 
	  
	    
		$output_form = '';
		
		// Extract the attributes
		extract(shortcode_atts( array(
				'attr1' => 'foo', //foo is a default value
				'attr2' => 'bar'
		), $atts) );
		// you can now access the attribute values using $attr1 and $attr2
	  
	 
			  
	   
	  /* Strip "<form>" tag from the string */

	  $wppd_custom_form = preg_replace( '/<form(.*?)>/s', '', $wppd_custom_form ); // Remove opening "<form>" tag
	  $wppd_custom_form = preg_replace( '/<\/form>/s', '', $wppd_custom_form ); // Remove closing "</form>" tag
	  
	  /**
	   * 	Paypal Fields
	   */
	  
	  $wppd_custom_paypal_fields = '';
	
	  
	  $wppd_custom_paypal_fields 	.=		'<input type="hidden" name="cmd" value="_ext-enter">';
	  $wppd_custom_paypal_fields 	.=		'<input type="hidden" name="redirect_cmd" value="_xclick">';
	  $wppd_custom_paypal_fields 	.=		'<input type="hidden" name="business" value="'.$wp_pay_direct_business_name.'">';
	  $wppd_custom_paypal_fields 	.=		'<input type="hidden" name="item_name" value="Direct Payment">';
	  $wppd_custom_paypal_fields 	.=		'<input type="hidden" name="amount" id="amount" value="">'; 
	  $wppd_custom_paypal_fields 	.=		'<input type="hidden" name="quantity" value="1">';
	  $wppd_custom_paypal_fields 	.=		'<input type="hidden" name="currency_code" value="USD">';
	  $wppd_custom_paypal_fields 	.=		'<input type="hidden" name="rm" value="2" />';
	  $wppd_custom_paypal_fields 	.=		'<input type="hidden" name="no_note" value="1" />';
	  $wppd_custom_paypal_fields 	.=		'<input type="hidden" name="return" value="<success>">';
	  $wppd_custom_paypal_fields 	.=		'<input type="hidden" name="notify_url" value="<ipn>">';
	  $wppd_custom_paypal_fields 	.=		'<input type="hidden" name="cancel_return" value="<failed>">';
	  
	  
	  $wppd_custom_paypal_fields 	.=		'<div class="control-group component">
											  <label class="control-label">Amount (you need to pay)</label>
												  <div class="controls">
													  <div class="input-prepend">
													  		<input type="text" name="actualamount" id="actualamount" placeholder="0.00" />
													  		<div class="fees">+ <a target="_blank" href="https://www.paypal.com/helpcenter/main.jsp;jsessionid=cCJYNlpHPvQnjtbkWfMNhh6WjLFzKG1P2J7bLNpNThVY5cy9W1xC!-484686312?t=
                                solutionTab&amp;ft=homeTab&amp;ps=&amp;solutionId=164045&amp;locale=en_GB&amp;_dyncharset=UTF-8&amp;countrycode=IN&amp;cmd=_help&amp;serverInstance=9022"> Paypal Fees extra</a>: <span id="payFees"></span> </div>
													  </div>
											  	</div>
	 									   </div>';
	  
	  $wppd_custom_paypal_fields 	.=		'<div class="control-group component">
		  										<label class="control-label">Total Amount to be paid</label>
												  <div class="controls">
													  <div class="input-prepend">
													  	  <span id="feesTotal">0.00</span>
													  </div>
												  </div>
		  									</div>';
	  
	  $wppd_custom_paypal_fields 	.=		' <div class="control-group component">
		  										<label class="control-label">Email</label>
												  <div class="controls">
													  <div class="input-prepend">
													  	  <input type="text" name="email" placeholder="Email" />
													  </div>
												  </div>
		  									</div>';
	 
	  /**
	   * 	Form Attributes
	   */
	   
	  
	  $wppd_form_attributes = 	'<form action="'.$wppd_form_action.'" method="post" class="wppd-form">';
	  
	  $wppd_form_close 		 =	'<div class="control-group component">
		  										<label class="control-label">&nbsp;</label>
												  <div class="controls">
													  <div class="input-prepend">
													  	  <input type="Submit" name="payDirectSubmit" value="Submit" />
													  </div>
												  </div>
		  
	  										 </div>';
	  $wppd_form_close 		.=	'</form>';
	  
	  
	  /**
	   * 	Final form layout
	   */
	  
	  $output_form = $wppd_form_attributes . $wppd_custom_paypal_fields . $wppd_custom_form . $wppd_form_close;
	  
	   return $output_form;
	}
	
	
}
global $wppd_shortcode;
$wppd_shortcode = new WpPayDirectShortcode();