(function ($) {
	"use strict";
	$(function () {
		
		/*------------------------------------------------*
		 *  Calculating paypal charges on keyup event
		 * -----------------------------------------------*/
		
		// Timer for the timeout
		var initial;
		
		$( '#actualamount' ).keyup( function(){
			
			//wppd_loader.gif
			jQuery( '#payFees' ).html( '<img class="wppd_form_loader" src="'+ wppd_display.loader_url +'" alt="loading..."  />' );
			
			clearTimeout( initial );

			initial = window.setTimeout( function() {
				  
					calculateAmount();
					
				    }, 1500);
			
		return false;
			
		} );
		
	});
}(jQuery));

/*-------------------------------------*
 * Function to check Float values
 *------------------------------------*/

function isValidFloatNumber( num ) {
	
    if (!/^((\d+(\.\d*)?)|((\d*\.)?\d+))$/.test( num ) ) {
        return false
    }
    return true
}

/*-------------------------------------*
 * Function to calculate paypal charges
 *------------------------------------*/

function calculatePaypalTax( amount ){
	
	var tax = 0;
		
	tax = ( amount * 2.9 ) / 100;
	tax = tax + 0.30;
	
		
	return tax;
}

/*-------------------------------------------*
 *	Function to round off the float values
 *-------------------------------------------*/


function format_number( amount ) {
	
	var i = parseFloat( amount );
	if( isNaN( i ) ) { i = 0.00; }
	var minus = '';
	if( i < 0 ) { minus = '-'; }
	i = Math.abs(i);
	i = parseInt((i + .005) * 100);
	i = i / 100;
	s = new String(i);
	if(s.indexOf('.') < 0) { s += '.00'; }
	if(s.indexOf('.') == (s.length - 2)) { s += '0'; }
	s = minus + s;
	return s;
}



/*----------------------------------------------------*
 * 	Function to display total payable amount
 *----------------------------------------------------*/

function calculateAmount(){
	
	var totalamout = jQuery( '#actualamount' ).val();
	
	if( !isValidFloatNumber( totalamout ) ){
		
		alert( 'The number you enterd is not Valid, Please enter again' );
		jQuery( '#actualamount' ).val('');
		jQuery( '#payFees' ).html( '0.00' );
		jQuery( '#feesTotal' ).html( '0.00' );
        
        return false;
        
	} else {
		
		var tax = 0;
		totalamout = Number( totalamout );
		tax = calculatePaypalTax( totalamout );
		totalamout = format_number( totalamout + tax );
		tax = format_number( tax );
		jQuery( '#payFees' ).html( tax );
		jQuery( '#feesTotal' ).html( totalamout );
		
	}
	
}