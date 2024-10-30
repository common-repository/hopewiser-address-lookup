<?php

// Print Hopewiser Autocomplete form
function HopewiserAddrLookup_printGravFormsAutocomplete() {
	$hpwOptions = get_option('HPWAddrLookup_GeneralSettings'); // Get all the saved options
	$hpwFormOptions = get_option('HPWAddrLookup_Forms');

	$country = $hpwFormOptions["country"];
	$mainDataset = HPWAddrLookup_getMainDataset($country);
	$httpServer = HPWAddrLookup_getServer();

	$returnString = '
			<script>
				// AutoComplete Shortcode
				HPWAUTOC.FindAddress({
	   			auth: "'.$hpwOptions['authtoken'].'",
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
	print $returnString;
}

// Add AutoComplete with Gravity Forms if the post contains Gravity Forms shortcode
function HopewiserAddrLookup_addHpwCodesGravityForm() {	
	// If using Advanced Custom Fields
	if(class_exists('ACF')) {
		$fields = get_fields(false, false);

		if($fields):
			foreach( $fields as $name => $value ):
		    	if (strpos($value, '[gravityform') !== false) {
		     		add_action( 'wp_footer','HopewiserAddrLookup_printGravFormsAutocomplete');
		     		break;
		     	}
   		 	endforeach;
		endif;
	} else { // If not using ACF
		add_action( 'wp_footer','HopewiserAddrLookup_printGravFormsAutocomplete'); 
	}
}

add_filter( 'gform_field_input', 'HopewiserAddrLookup_addHPWInput', 10, 5 ); // If there is a Gravity Form. We can also use gform_form_tag
function HopewiserAddrLookup_addHPWInput( $input, $field, $value, $lead_id, $form_id ) {
    if ( $field->cssClass == 'addrBox' ) {
        $input = '<div class="ginput_container ginput_container_textarea">
  					<textarea id="addrBox" class="textarea medium" name="addrBox" aria-invalid="false" autocomplete="off"></textarea>
				 </div>';
    }
	HopewiserAddrLookup_addHpwCodesGravityForm();
    return $input;
}