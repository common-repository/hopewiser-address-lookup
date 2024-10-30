<div class="modal fade" id="hpwModal" tabindex="-1" role="dialog" aria-labelledby="hpwModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
         <h4 class="modal-title hpw-h4" id="hpwModalLabel">Possible Matches</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <p class="hpw-info" id="hpw-drillDownInfo"></p>
          <p class="hpw-info" id="hpw-status"></p>
        </div>
        <div class="form-group">
          <select id="hpw-possibles" name="possibles" size="8" class="form-control" ondblclick="HPWINTL.btnOK_onclick()" onkeypress="HPWINTL.selPossibles_onkeypress(event)">
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="hpw-btnOK" onclick="HPWINTL.btnOK_onclick()">Select</button>
        <button type="button" class="btn btn-default" id="hpw-btnCancel" onblur="HPWINTL.resetdrillDownDesc()" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>
	function inputbilling_onkeypress(e) {
    	var ch = e.charCode ? e.charCode : e.keyCode;
		if(document.getElementById("btnFindAddress")) {
    		document.getElementById("btnFindAddress").disabled = false;
		}

		// If ENTER is pressed
    	if(ch == 0xD) {
			event.preventDefault(); // Prevent WooCommerce going to Payment
			if(document.getElementById("btnFindAddress")) {
    			document.getElementById("btnFindAddress").click();
			}
		}
  	}

	// Enable button after user types an address to search
	function inputshipping_onkeypress(e) {
    	var ch = e.charCode ? e.charCode : e.keyCode;

		if(document.getElementById("btnFindAddressShipping")) {
			document.getElementById("btnFindAddressShipping").disabled = false;
		}

		// If ENTER is pressed
    	if(ch == 0xD) {
			event.preventDefault(); // Prevent WooCommerce going to Payment
			if(document.getElementById("btnFindAddressShipping")) {
				document.getElementById("btnFindAddressShipping").click();
			}
		}
  	}
</script>


<?php
print '
	<input type="hidden" id="addressOutput3" name="addressOutput3">
	<input type="hidden" id="addressOutput4" name="addressOutput4">

	<script type="text/javascript">
	function callFindAddress(addressType, mode) {
		var inputAddress = addressType + "_address_1";';

  	print '
   		HPWINTL.FindAddress({
			auth: "'.$hpwOptions["authtoken"].'",
			server: "'.$httpServer.'",
			input: "input" + addressType,  // required
			countryListBox: "inpCountryList_" + addressType,
			topCountries: "",
 			output: {
	    		line1:    addressType + "_address_1",     // required
	        	line2:    addressType + "_address_2",   // required
	        	line3:    "addressOutput3",     // optional
				line4:    "addressOutput4",     // optional
				//line5:    "addressOutput5",     // optional
				//line6:    "addressOutput6",     // optional
				//line7:    "addressOutput7",     // optional
	        	//line4:    addressType + "_city",     // optional
				//line5: 	  addressType + "_state",
				//line6:	  addressType + "_postcode"
	      	},
			detail: {
				Town: addressType + "_city",
				County: addressType + "_state",
				Postcode: addressType + "_postcode",
				ReserveOrganisationLine: "AsRequired",
	      		IncludeCounty: "AsRequired",
	      		TownFormat: "Uppercase",
			},
			Mode: mode,
			success: processAfter,
	   	});
	';

	print '
   		// Function to be called after the call to FindAddress()
		// - Select/Show the matched State address to the user/on screen
		// - Check if Address Label 3 exists. If it is, concatenate to Address Label 2
   		function processAfter() {
			addressLine2 = document.getElementById(addressType + "_address_2").value.trim();
			addressLine3 = document.getElementById("addressOutput3").value.trim();
			addressLine4 = document.getElementById("addressOutput4").value.trim();
			locality = document.getElementById(addressType + "_city").value.trim();
			postcode = document.getElementById(addressType + "_postcode").value.trim();

			// If address line 2 contains Locality, remove it
			if(addressLine2 !== "" && addressLine2.toUpperCase().includes(locality.toUpperCase())) {
				document.getElementById(addressType + "_address_2").value = "";
			// If address line 2 contains Locality, remove it
			} else if (addressLine2 !== "" && addressLine2.toUpperCase().includes(postcode.toUpperCase())) {
				document.getElementById(addressType + "_address_2").value = "";
			// If address line 2 contains important information, we also check for line 3, and so on
			} else {
				// If address line 3 is not empty and does not contain Locality
				if(addressLine3 !== "" && !addressLine3.toUpperCase().includes(locality.toUpperCase())) {
					// and does not contain Postcode
					if(addressLine3 !== "" && !addressLine3.toUpperCase().includes(postcode.toUpperCase())) {
						document.getElementById(addressType + "_address_2").value =
							document.getElementById(addressType + "_address_2").value + ", " + document.getElementById("addressOutput3").value;
					}
					// If address line 4 is not empty and does not contain Locality
					if(addressLine4 !== "" && !addressLine4.toUpperCase().includes(locality.toUpperCase())) {
						// and does not contain Postcode
						if(addressLine4 !== "" && !addressLine4.toUpperCase().includes(postcode.toUpperCase())) {
							document.getElementById(addressType + "_address_2").value =
								document.getElementById(addressType + "_address_2").value + ", " + document.getElementById("addressOutput4").value;
						}
					}
				}
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



function callAddrLookupBilling($fields) {
	print '<script>
		function btnFindAddress_onclick() {
			callFindAddress("billing", HPWINTL.ADDRESS_SEARCH);
		}
		</script>';


	// Add our Address Lookup Search form & Button
	print '<div id="intladdrLookup_billingcountry"><p class="form-row form-row-wide" data-priority="30">';
	print '
	   		<label>Country<abbr class="required" title="required">*</abbr></label>
			<select id="inpCountryList_billing"> </select>
			</p></div>

			<div class="woocommerce-billing-fields__field-wrapper">
				<p class="form-row form-row-wide" data-priority="30">
					<label>Search Address:</label>
					<input id="inputbilling" type="text" class="input-text" onkeypress="inputbilling_onkeypress(event)"/>
    				<button type="button" id="btnFindAddress" data-toggle="modal" data-target="#hpwModal" data-backdrop="static" onclick="btnFindAddress_onclick()" style="border-top-style: solid;margin-top: 10px;">Find Address</button>
				</p>
			</div>';
}

function callAddrLookupShipping($fields) {
	print '<script>
		function btnFindAddressShipping_onclick() {
			callFindAddress("shipping", HPWINTL.ADDRESS_SEARCH);
		}
		</script>';


	// Add our Address Lookup Search form & Button
	print '<div id="intladdrLookup_shippingcountry"><p class="form-row form-row-wide" data-priority="30">';
	print '
	   		<label>Country<abbr class="required" title="required">*</abbr></label>
			<select id="inpCountryList_shipping"> </select>
			</p></div>

			<div class="woocommerce-billing-fields__field-wrapper">
				<p class="form-row form-row-wide" data-priority="30">
					<label>Search Address:</label>
					<input id="inputshipping" type="text" class="input-text" onkeypress="inputshipping_onkeypress(event)"/>
    				<button type="button" id="btnFindAddressShipping" data-toggle="modal" data-target="#hpwModal" data-backdrop="static" onclick="btnFindAddressShipping_onclick()" style="border-top-style: solid;margin-top: 10px;">Find Address</button>
				</p>
			</div>';
}


// Hide the country label + dropdown, also cloning WooCommerce country list
function processBillingCountry() {
?>
	<script>
		jQuery("#inpCountryList_billing").change(function() {
			// Set WooCommerce country value to be the same as International Address Lookup selected country
			document.getElementById("billing_country").value = document.getElementById("inpCountryList_billing").value;
			jQuery( '#billing_country' ).trigger( 'change' );
		});

		// Fill Addr Lookup Country list with WooCommerce's and process
		// If multiple countries allowed (i.e appearing as a dropdown)
		if(document.getElementById('billing_country').tagName == 'SELECT') {

			jQuery('#inpCountryList_billing').append(jQuery('#billing_country').html());
			callFindAddress("billing", HPWINTL.USE_OWN_CODES);

			// Hide WooCommerce default Country dropdown
			document.getElementById("billing_country_field").style.display = "none";
		// else if only a single country (appearing as a label)
		} else {
			// Hide our custom dropdown selection as it's unnecessary
			document.getElementById('intladdrLookup_billingcountry').style.display = "none";

			// Create an Option object
        	var opt = document.createElement("option");
        	opt.text = document.getElementById("billing_country").value;
        	opt.value = document.getElementById("billing_country").value;
        	document.getElementById("inpCountryList_billing").options.add(opt);

			callFindAddress("billing", HPWINTL.USE_OWN_CODES);
		}
	</script>
<?php
}

// Hide the country label + dropdown, also cloning WooCommerce country list
function processShippingCountry() {
?>
	<script>
		jQuery("#inpCountryList_shipping").change(function() {
			// Set WooCommerce country value to be the same as International Address Lookup selected country
			document.getElementById("shipping_country").value = document.getElementById("inpCountryList_shipping").value;
			jQuery( '#shipping_country' ).trigger( 'change' );
		});

		// Fill Addr Lookup Country list with WooCommerce's and process
		// If multiple countries allowed (i.e appearing as a dropdown)
		if(document.getElementById('shipping_country').tagName == 'SELECT') {

			jQuery('#inpCountryList_shipping').append(jQuery('#shipping_country').html());
			callFindAddress("shipping", HPWINTL.USE_OWN_CODES);

			// Hide WooCommerce default Country dropdown
			document.getElementById("shipping_country_field").style.display = "none";
		// else if only a single country (appearing as a label)
		} else {
			// Hide our custom dropdown selection as it's unnecessary
			document.getElementById('intladdrLookup_shippingcountry').style.display = "none";

			// Create an Option object
        	var opt = document.createElement("option");
        	opt.text = document.getElementById("shipping_country").value;
        	opt.value = document.getElementById("shipping_country").value;
        	document.getElementById("inpCountryList_shipping").options.add(opt);

			callFindAddress("shipping", HPWINTL.USE_OWN_CODES);
		}
	</script>
<?php
}

//print '<script> callFindAddress("shipping", HPWINTL.USE_ALPHA2_CODES); </script>';
//print '<script> callFindAddress("billing", HPWINTL.USE_ALPHA2_CODES); </script>';
?>
