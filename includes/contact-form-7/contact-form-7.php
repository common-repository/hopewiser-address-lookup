<?php

// Print Hopewiser Autocomplete form
function printCF7Autocomplete() {
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

// Add AutoComplete with Contact Form 7, only if post contains contact form 7 forms
function addHpwCodesCF7() {
	global $post;
	if (has_shortcode( $post->post_content, 'contact-form-7')) {
		add_action( 'wp_footer','printCF7Autocomplete');
		return;
	}

	// If using Advanced Custom Fields
	if(class_exists('ACF')) {
		$fields = get_fields(false, false);

		if($fields):
			foreach( $fields as $name => $value ):
				if(is_string($value)) {
		    		if (strpos($value, '[contact-form-7') !== false) {
			     		add_action( 'wp_footer','printCF7Autocomplete');
			     		break;
			     	}
				}
   		 	endforeach;
		endif;
	}
}

add_action( 'wpcf7_enqueue_scripts', 'addHpwCodesCF7', 20 );
