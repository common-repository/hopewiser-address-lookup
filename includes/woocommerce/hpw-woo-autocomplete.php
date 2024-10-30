<?php
//function printJSCodes() {
	print '
	<script type="text/javascript">
	// Call Hopewiser FindAddress API
	// If selected country is supported by our API, also check if the account has a MAF set for that country.
    // If not, make sure to disable autocompleting text calls.
	function callFindAddress(selectedCountryCode, addressType) {
		if(selectedCountryCode == "GB") {';
			if(isset($hpwOptions['ukmaf']) && $hpwOptions['ukmaf'] != "") {
				print '
					var mainDataset = "'.$hpwOptions['ukmaf'].'";
					var inputAddress = addressType + "_address_1";';
			} else {
				print '
					// Disable further autocompleting for unsupported country
					jQuery(function(){
						jQuery( "#" + addressType + "_address_1").autocomplete({
			        		source: []
			        	});
					});
					return; // Do not call FindAddress()	';
			}
	print '
		} else if(selectedCountryCode == "AU") {';
			if(isset($hpwOptions['aumaf']) && $hpwOptions['aumaf'] != "") {
				print '
					var mainDataset = "'.get_option("HPWAddrLookup_GeneralSettings")['aumaf'].'";
					var inputAddress = addressType + "_address_1";';
			} else {
				print '
					// Disable further autocompleting for unsupported country
					jQuery(function(){
						jQuery( "#" + addressType + "_address_1").autocomplete({
				        	source: []
				        });
					});
					return; // Do not call FindAddress()	';
			}
	print '
		} else if(selectedCountryCode == "NZ") {';
			if(isset($hpwOptions['nzmaf']) && $hpwOptions['nzmaf'] != "") {
				print '
					var mainDataset = "'.get_option("HPWAddrLookup_GeneralSettings")['nzmaf'].'";
					var inputAddress = addressType + "_address_1";';
			} else {
				print '
					// Disable further autocompleting for unsupported country
					jQuery(function(){
						jQuery( "#" + addressType + "_address_1").autocomplete({
				        	source: []
				        });
					});
					return; // Do not call FindAddress()	';
			}
	print '
		} else if(selectedCountryCode == "IE") {';
			if(isset($hpwOptions['irlmaf']) && $hpwOptions['irlmaf'] != "") {
				print '
					var mainDataset = "'.get_option("HPWAddrLookup_GeneralSettings")['irlmaf'].'";
					var inputAddress = addressType + "_address_1";';
			} else {
				print '
					// Disable further autocompleting for unsupported country
					jQuery(function(){
						jQuery( "#" + addressType + "_address_1").autocomplete({
							source: []
						});
					});
					return; // Do not call FindAddress()	';
			}
	print '
		} else {
			// Disable further autocompleting for unsupported country
			jQuery(function(){
				jQuery( "#" + addressType + "_address_1").autocomplete({
               	source: []
            	});
			});
			// Trying to change text placeholders dynamically but does not seem to work
			// Leaving these codes here to come back to them next time
			// document.getElementById("billing_address_1").placeholder = "Test";
			// jQuery( "#" + addressType + "_address_1").attr("placeholder", "Type something").val("").focus().blur();
			// jQuery( "#" + addressType + "_address_1").attr("placeholder", "Test");

			return; // Do not call FindAddress()
		}';

	print '
        // Address Line 3 (if any) will be set to an extra textfield addressOutput3, hidden from view
		// and will be concatenated to Address Line 2 inside the processAfter() function
		HPWAUTOC.FindAddress({
	   		auth: "'.$hpwOptions['authtoken'].'",
			server: "'.$httpServer.'",
			dataset: mainDataset,
    		input: inputAddress,
		    outputlines: 6,
    		output: {
      			line1:    addressType + "_address_1",
	      		line2:    addressType + "_address_2",
	      		line3:    "addressOutput3",
	      		line4:    "",
	      		line5:    "",
	      		line6:    "",
      			line7:    "",
      			line8:    "",
      			line9:    ""
    		},
    		success: processAfter,
			LabelFormat: "FixedTown",
			detail: {
	  			Town: addressType + "_city",
      			County: addressType + "_state",
      			Postcode:  addressType + "_postcode"
    		},
    		TownFormat: "Uppercase"
  			}); ';
	print '

   		// Function to be called after the call to FindAddress()
		// - Select/Show the matched State address to the user/on screen
		// - Check if Address Label 3 exists. If it is, concatenate to Address Label 2
   		function processAfter() {
			if(document.getElementById("addressOutput3").value.trim() !== "") {
				document.getElementById(addressType + "_address_2").value =
						document.getElementById(addressType + "_address_2").value + ", " + document.getElementById("addressOutput3").value;
			}

			// If state is a dropdown, not a text field
			if(document.getElementById("select2-" + addressType + "_state-container") != null) {
				document.getElementById("select2-" + addressType + "_state-container").innerHTML = document.getElementById(addressType + "_state").value;
			}

			// Refresh the cart box
			'."jQuery('body').trigger('update_checkout');".'
		}
	}
	</script>
	';

	return;
//}

// Call Hopewiser FindAddress for Billing Addresses (both on Checkout and My Account)
function callAutoCompleteBilling($fields) {
//-----------------------------------
	// Auto select MAF depending on the country selected
	print '

	<!-- To capture Address Line 3 from Hopewiser AddressLookup
	  If exists, will be concatenated to Address Line 2 -->
	<input type="hidden" id="addressOutput3" name="addressOutput3">


	<script>
	// Get the default selected country when the page is first loaded
	var selectedCountryCode = document.getElementById("billing_country").value;
	callFindAddress(selectedCountryCode, "billing");

	// If user changes the country manually on the dropdown
	jQuery(document).ready(function($){
    	jQuery("select#billing_country").change(function(){
			selectedCountryCode = jQuery("select#billing_country").val();
			callFindAddress(selectedCountryCode, "billing");
		 });
	});

	</script> ';

	return $fields;
}

// Call Hopewiser FindAddress for Shipping (both on Checkout and My Account)
function callAutoCompleteShipping($fields) {
//-----------------------------------
	// Auto select MAF depending on the country selected
	print '

	<!-- To capture Address Line 3 from Hopewiser AddressLookup
	  If exists, will be concatenated to Address Line 2 -->
	<input type="hidden" id="addressOutput3" name="addressOutput3">

	<script>
	// Get the default selected country when the page is first loaded
	var selectedCountryCode = document.getElementById("shipping_country").value;
	callFindAddress(selectedCountryCode, "shipping");

	// If user changes the country manually on the dropdown
	jQuery(document).ready(function($){
    	jQuery("select#shipping_country").change(function(){
			selectedCountryCode = jQuery("select#shipping_country").val();
			callFindAddress(selectedCountryCode, "shipping");
		 });
	});

	</script> ';

	return $fields;
}

// AutoComplete for WooCommerce Admin Order page - Billing section
function callAutoCompleteAdminBilling($fields) {
//-----------------------------------
	
	// Auto select MAF depending on the country selected
	print '
	<!-- To capture Address Line 3 from Hopewiser AddressLookup
	  If exists, will be concatenated to Address Line 2 -->
	<input type="hidden" id="addressOutput3" name="addressOutput3">


	<script>	
	// Get the default selected country when the page is first loaded
	var selectedCountryCode = document.getElementById("_billing_country").value;
	callFindAddress(selectedCountryCode, "_billing");

	// If user changes the country manually on the dropdown
	jQuery(document).ready(function($){
    	jQuery("select#_billing_country").change(function(){
			selectedCountryCode = jQuery("select#_billing_country").val();
			callFindAddress(selectedCountryCode, "_billing");
		 });
	});

	</script> ';

	return $fields;
}
// AutoComplete for WooCommerce Admin Order page - Shipping section
function callAutoCompleteAdminShipping($fields) {
//-----------------------------------
	// Auto select MAF depending on the country selected
	print '

	<!-- To capture Address Line 3 from Hopewiser AddressLookup
	  If exists, will be concatenated to Address Line 2 -->
	<input type="hidden" id="addressOutput3" name="addressOutput3">

	<script>

	// Get the default selected country when the page is first loaded
	var selectedCountryCode = document.getElementById("_shipping_country").value;
	callFindAddress(selectedCountryCode, "_shipping");

	// If user changes the country manually on the dropdown
	jQuery(document).ready(function($){
    	jQuery("select#_shipping_country").change(function(){
			selectedCountryCode = jQuery("select#_shipping_country").val();
			callFindAddress(selectedCountryCode, "_shipping");
		 });
	});

	</script> ';

	return $fields;
}

?>
