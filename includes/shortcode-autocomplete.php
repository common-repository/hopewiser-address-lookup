<?php

// Print Hopewiser Autocomplete form
function HPWAddrLookup_printAutocForm($atts = []) {
	if(empty($atts)) {
		print "ERROR: Please define a Country to match on your shortcode.<br/><br/>";
		return;
	}

	$hpwAddrLookup_options = get_option('HPWAddrLookup_GeneralSettings'); // Get all the saved options

	$atts = array_change_key_case((array)$atts, CASE_LOWER);
	$country = strtoupper($atts['country']);
	$isCountrySupported = false;

	$mainDataset = HPWAddrLookup_getMainDataset($country);
	if($mainDataset)
		$isCountrySupported = true;
	else
		return "ERROR: Country code `$country` is not supported. Please check the Administration settings for supported countries.<br/><br/>";

	$httpServer = HPWAddrLookup_getServer();
	
$returnString = '
<p>
<div>
  <table>
     <tr>
      <td><textarea name="addrBox" id="addrBox" cols="70" rows="4"></textarea></td>
    </tr>
  </table>
</div>';

	// If the country supports AutoComplete, call FindAddress() to auto complete
	if($isCountrySupported == true) {
		$returnString .= '
			<script>
				// AutoComplete Shortcode
				HPWAUTOC.FindAddress({
	   			auth: "'.$hpwAddrLookup_options['authtoken'].'",
				server: "'.$httpServer.'",				
				dataset: "'.$mainDataset.'",
    			input: "addrBox",
		    	outputlines: 6,
    			output: {
	      			line1:    "addrBox",
		      		line2:    "addrBox",
		      		line3:    "addrBox",
		      		line4:    "addrBox",
		      		line5:    "addrBox",
		      		line6:    "addrBox",
	      			line7:    "",
	      			line8:    "",
	      			line9:    ""
	    		},
	    		TownFormat: "Uppercase"
	  			});
			</script>';
	}
	
	return $returnString;

}

add_shortcode('hpw-autocomplete', 'HPWAddrLookup_printAutocForm');
