<div class="modal fade" id="hpwModal" tabindex="-1" role="dialog" aria-labelledby="hpwModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
		<h4 class="modal-title hpw-h4" id="hpwModalLabel">Possible Matches</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		<div class="form-group">
          <p class="hpw-info" id="hpw-drillDownInfo"></p>
          <p class="hpw-info" id="hpw-status"></p>
        </div>
        <div class="form-group">
          <select id="hpw-possibles" name="possibles" size="8" class="form-control" ondblclick="HPW.btnOK_onclick()" onkeypress="HPW.selPossibles_onkeypress(event)">
          </select>
        </div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="hpw-btnOK" onclick="HPW.btnOK_onclick()">Select</button>
        <button type="button" class="btn btn-default" onblur="HPW.resetdrillDownDesc()" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>
	var mainDataset = "";

	// Enable button after user types an address to search
	function inputbilling_onkeypress(e) {
    	var ch = e.charCode ? e.charCode : e.keyCode;
		if(document.getElementById("btnFindAddress_billing")) {
    		document.getElementById("btnFindAddress_billing").disabled = false;
		}

		// If ENTER is pressed
    	if(ch == 0xD) {
			event.preventDefault(); // Prevent WooCommerce going to Payment
			if(document.getElementById("btnFindAddress_billing")) {
    			document.getElementById("btnFindAddress_billing").click();
			}
		}
  	}

	// Enable button after user types an address to search
	function inputshipping_onkeypress(e) {
    	var ch = e.charCode ? e.charCode : e.keyCode;

		if(document.getElementById("btnFindAddress_shipping")) {
			document.getElementById("btnFindAddress_shipping").disabled = false;
		}

		// If ENTER is pressed
    	if(ch == 0xD) {
			event.preventDefault(); // Prevent WooCommerce going to Payment
			if(document.getElementById("btnFindAddress_shipping")) {
				document.getElementById("btnFindAddress_shipping").click();
			}
		}
  	}

	// Upon first screen loading or when a country is changed, process it to hide/show interface
	// and set the variables for FindAddress
	function processCountry(countryCode, addressType) {
		<?php $hpwOptions = get_option('HPWAddrLookup_GeneralSettings'); // Get all the saved options ?>

		if(countryCode == "GB") {
			<?php
				if(isset($hpwOptions['ukmaf']) && $hpwOptions['ukmaf'] != "") {
					print '
					mainDataset = "'.get_option("HPWAddrLookup_GeneralSettings")['ukmaf'].'";
					showHideAddrLookup(true, addressType);
					inputAddress = addressType + "_address_1";
					';
				} else {
					print '
						showHideAddrLookup(false, addressType);
					';
				}
			?>

		} else if(countryCode == "AU") {
			<?php
				if(isset($hpwOptions['aumaf']) && $hpwOptions['aumaf'] != "") {
					print '
					mainDataset = "'.get_option("HPWAddrLookup_GeneralSettings")['aumaf'].'";
					showHideAddrLookup(true, addressType);
					inputAddress = addressType + "_address_1";
					';
				} else {
					print '
						showHideAddrLookup(false, addressType);
					';
				}
			?>
		} else if(countryCode == "NZ") {
			<?php
				if(isset($hpwOptions['nzmaf']) && $hpwOptions['nzmaf'] != "") {
					print '
					mainDataset = "'.get_option("HPWAddrLookup_GeneralSettings")['nzmaf'].'";
					showHideAddrLookup(true, addressType);
					inputAddress = addressType + "_address_1";
					';
				} else {
					print '
						showHideAddrLookup(false, addressType);
					';
				}
			?>
		} else if(countryCode == "IE") {
			<?php
				if(isset($hpwOptions['irlmaf']) && $hpwOptions['irlmaf'] != "") {
					print '
					mainDataset = "'.get_option("HPWAddrLookup_GeneralSettings")['iremaf'].'";
					showHideAddrLookup(true, addressType);
					inputAddress = addressType + "_address_1";
					';
				} else {
					print '
						showHideAddrLookup(false, addressType);
					';
				}
			?>
		} else {
			showHideAddrLookup(false, addressType);
		}
	}

	// Show or Hide Address Lookup search functionality based on whether admin has the country MAF
	function showHideAddrLookup(isShow, addrType) {
		if(isShow) {
			document.getElementById("btnFindAddress_" + addrType).style.display = "block";
			document.getElementById("input" + addrType).style.display = "block";
			document.getElementById("input" + addrType + "_label").style.display = "block";
		} else {
			document.getElementById("btnFindAddress_" + addrType).style.display = "none";
			document.getElementById("input" + addrType).style.display = "none";
			document.getElementById("input" + addrType + "_label").style.display = "none";
		}
	}
</script>


<?php
print '
<input type="hidden" id="addressOutput3" name="addressOutput3">

	<script type="text/javascript">
	// Call Hopewiser FindAddress API
	// If selected country is supported by our API, also check if the account has a MAF set for that country.
    // If not, make sure to disable autocompleting text calls.
	function callFindAddress(selectedCountryCode, addressType) {
		';

	print '
   		HPW.FindAddress({
			auth: "'.$hpwOptions["authtoken"].'",
			server: "'.$httpServer.'",
			dataset: mainDataset,
			input: "input" + addressType,  // required
 			output: {
	    		line1:    addressType + "_address_1",     // required
	        	line2:    addressType + "_address_2",   // required
	        	line3:    "addressOutput3",     // optional
	        	line4:    "",     // optional
	        	town:     addressType + "_city",         // required
	        	county:   addressType + "_state",        // optional
	        	postcode: addressType + "_postcode",     // required
	        	country:  ""       // optional
	      	},
			success:processAfter,
	      	ReserveOrganisationLine: "AsRequired",
	      	IncludeCounty: "AsRequired",
	      	TownFormat: "Uppercase",
	      	Debug: false
	   	});
	';

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



function callAddrLookupBilling($fields) {
	print '<script>
		function btnFindAddress_billing_onclick() {
			var selectedCountryCode = document.getElementById("billing_country").value;
			callFindAddress(selectedCountryCode, "billing");
		}
		</script>';

	// Add our Address Lookup Search form & Button
	print '<div id="addrLookup_billingcountry"> <p class="form-row form-row-wide" data-priority="30">';
	print '
	   		<label>Country<abbr class="required" title="required">*</abbr></label>
			<select id="inpCountryList_billing"> </select>
			</p></div>

			<div class="woocommerce-billing-fields__field-wrapper">
				<p class="form-row form-row-wide" data-priority="30">
					<label id="inputbilling_label">Search Address:</label>
					<input id="inputbilling" type="text" class="input-text" onkeypress="inputbilling_onkeypress(event)"/>
		    		<button type="button" id="btnFindAddress_billing" data-toggle="modal" data-target="#hpwModal" data-backdrop="static" onclick="btnFindAddress_billing_onclick()" style="border-top-style: solid;margin-top: 10px;">Find Address</button>
				</p>
			</div>';
}

function callAddrLookupShipping($fields) {
	print '<script>
		function btnFindAddress_shipping_onclick() {
			var selectedCountryCode = document.getElementById("shipping_country").value;
			callFindAddress(selectedCountryCode, "shipping");
		}
		</script>';

	// Add our Address Lookup Search form & Button
	print '<div id="addrLookup_shippingcountry"><p class="form-row form-row-wide" data-priority="30">';
	print '
	   		<label>Country<abbr class="required" title="required">*</abbr></label>
			<select id="inpCountryList_shipping"> </select>
			</p></div>

			<div class="woocommerce-billing-fields__field-wrapper">
				<p class="form-row form-row-wide" data-priority="30">
					<label id="inputshipping_label">Search Address:</label>
					<input id="inputshipping" type="text" class="input-text" onkeypress="inputshipping_onkeypress(event)"/>
    				<button type="button" id="btnFindAddress_shipping" data-toggle="modal" data-target="#hpwModal" data-backdrop="static" onclick="btnFindAddress_shipping_onclick()" style="border-top-style: solid;margin-top: 10px;">Find Address</button>
				</p>
			</div>';

}

// Hide the country label + dropdown, also cloning WooCommerce country list
function processBillingCountry() {
	$hpwOptions = get_option('HPWAddrLookup_GeneralSettings'); // Get all the saved options
?>
	<script>
		// When the Country is changed/selected
		jQuery("#inpCountryList_billing").change(function() {
			// Set WooCommerce country value to be the same as International Address Lookup selected country
			document.getElementById("billing_country").value = document.getElementById("inpCountryList_billing").value;

			var selectedCountryCode = document.getElementById("billing_country").value;
			processCountry(selectedCountryCode, "billing");

			jQuery('#billing_country').trigger('change');
		});

		// Fill Addr Lookup Country list with WooCommerce's and process
		// If multiple countries allowed (i.e appearing as a dropdown)
		if(document.getElementById('billing_country').tagName == 'SELECT') {
			jQuery('#inpCountryList_billing').append(jQuery('#billing_country').html());
			var selectedCountryCode = document.getElementById("inpCountryList_billing").value;
			processCountry(selectedCountryCode, "billing");

			// Hide WooCommerce default Country dropdown
			document.getElementById("billing_country_field").style.display = "none";
		// else if only a single country (appearing as a label)
		} else {
			// Hide our custom dropdown selection as it's unnecessary
			document.getElementById('addrLookup_billingcountry').style.display = "none";

			var selectedCountryCode = document.getElementById("billing_country").value;
			processCountry(selectedCountryCode, "billing");
		}

	</script>
<?php
}

// Hide the country label + dropdown, also cloning WooCommerce country list
function processShippingCountry() {
	$hpwOptions = get_option('HPWAddrLookup_GeneralSettings'); // Get all the saved options
?>
	<script>
		jQuery("#inpCountryList_shipping").change(function() {
			// Modified by MA
			// Set WooCommerce country value to be the same as International Address Lookup selected country
			document.getElementById("shipping_country").value = document.getElementById("inpCountryList_shipping").value;

			var selectedCountryCode = document.getElementById("shipping_country").value;
			processCountry(selectedCountryCode, "shipping");

			jQuery('#shipping_country').trigger('change');
		});

		if(document.getElementById('shipping_country').tagName == 'SELECT') {
			// Fill Addr Lookup Country list with WooCommerce's and process
			jQuery('#inpCountryList_shipping').append(jQuery('#shipping_country').html());
			var selectedCountryCode = document.getElementById("inpCountryList_shipping").value;
			processCountry(selectedCountryCode, "shipping");

			// Hide WooCommerce default Country dropdown
			document.getElementById("shipping_country_field").style.display = "none";
		// else if only a single country (appearing as a label)
		} else {
			// Hide our custom dropdown selection as it's unnecessary
			document.getElementById('addrLookup_shippingcountry').style.display = "none";

			var selectedCountryCode = document.getElementById("shipping_country").value;
			processCountry(selectedCountryCode, "shipping");
		}
	</script>
<?php
}
?>
