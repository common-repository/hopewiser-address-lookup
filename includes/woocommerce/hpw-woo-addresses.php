<?php

// Force Woocommerce to change Australian states from full-naming to abbreviated states
add_filter( 'woocommerce_states', 'custom_woocommerce_states' );
function custom_woocommerce_states( $states ) {
  // Change Australia States to use Abbreviated ones
  $states['AU'] = array(
	'ACT' => 'ACT',
    'NSW' => 'NSW',
	'NT' => 'NT',
	'QLD' => 'QLD',
	'SA' => 'SA',
	'TAS' => 'TAS',
	'VIC' => 'VIC',
	'WA' => 'WA'
  );

  return $states;
}

// Include Hopewiser-related JavaScript function such as FindAddress
add_action( 'woocommerce_before_checkout_form', 'add_hopewiser_functions'); // Cart checkout
add_action( 'woocommerce_before_edit_account_address_form', 'add_hopewiser_functions'); // My Account Edit Billing Address
add_action( 'woocommerce_admin_order_data_after_order_details', 'add_hopewiser_functions'); // Admin Order Page

function add_hopewiser_functions() {
	$hpwOptions = get_option('HPWAddrLookup_GeneralSettings'); // Get all the saved options
	$hpwWooOptions = get_option("HPWAddrLookup_Woo"); // Get WooCommerce options
	
	if(isset($hpwOptions["server"])) {
	   	if($hpwOptions["server"] == "Australia")
   			$httpServer = "https://cloud.hopewiser.com.au";
		else
			$httpServer = "https://cloud.hopewiser.com";
	} else {
		$httpServer = "https://cloud.hopewiser.com";		
	}
	if($hpwWooOptions['solution'] == "AutoComplete") {
		require_once plugin_dir_path(__FILE__) . 'hpw-woo-autocomplete.php';

		// Add relevant call to Hopewiser's FindAddress() on these WooCommerce sections
		add_action( 'woocommerce_after_edit_address_form_billing', 'callAutoCompleteBilling'); // My Account Edit Billing Address
		add_action( 'woocommerce_after_edit_address_form_shipping', 'callAutoCompleteShipping'); // My Account Edit Shipping Address

		add_action( 'woocommerce_after_checkout_billing_form', 'callAutoCompleteBilling'); // Checkout Billing Address
		add_action( 'woocommerce_after_checkout_shipping_form', 'callAutoCompleteShipping'); // Checkout Shipping Address
		
	   // Add on the Admin manual order page
		//add_action( 'woocommerce_ajax_get_customer_details', 'callAutoCompleteAdminBilling'); // Admin
		add_action( 'woocommerce_admin_order_data_after_billing_address', 'callAutoCompleteAdminBilling'); // Admin
		add_action( 'woocommerce_admin_order_data_after_shipping_address', 'callAutoCompleteAdminShipping'); // Admin

	} else if($hpwWooOptions['solution'] == "Address Lookup") {
		require_once plugin_dir_path(__FILE__) . 'hpw-woo-addrlookup.php';

		add_action( 'woocommerce_before_checkout_billing_form', 'callAddrLookupBilling'); // Checkout Edit Billing Address
		add_action( 'woocommerce_before_checkout_shipping_form', 'callAddrLookupShipping'); // Checkout Edit Billing Address

		add_action( 'woocommerce_before_edit_address_form_billing', 'callAddrLookupBilling'); // My Account - Edit Billing Address
		add_action( 'woocommerce_before_edit_address_form_shipping', 'callAddrLookupShipping'); // My Account - Edit Shipping Address

		// Hide WooCommerce Country Default Dropdown
		add_action('woocommerce_after_checkout_billing_form', 'processBillingCountry'); // Checkout Edit Billing Address
		add_action('woocommerce_after_checkout_shipping_form', 'processShippingCountry'); // Checkout Edit Billing Address
		add_action( 'woocommerce_after_edit_address_form_billing', 'processBillingCountry'); // My Account - Edit Billing Address
		add_action( 'woocommerce_after_edit_address_form_shipping', 'processShippingCountry'); // My Account - Edit Shipping Address
	} else if($hpwWooOptions['solution'] == "International Address Lookup") {
		require_once plugin_dir_path(__FILE__) . 'hpw-woo-intl-addrlookup.php';

		add_action( 'woocommerce_before_checkout_billing_form', 'callAddrLookupBilling'); // Checkout Edit Billing Address
		add_action( 'woocommerce_before_checkout_shipping_form', 'callAddrLookupShipping'); // Checkout Edit Billing Address

		add_action( 'woocommerce_before_edit_address_form_billing', 'callAddrLookupBilling'); // My Account - Edit Billing Address
		add_action( 'woocommerce_before_edit_address_form_shipping', 'callAddrLookupShipping'); // My Account - Edit Billing Address

		// Hide WooCommerce Country Default Dropdown
		add_action('woocommerce_after_checkout_billing_form', 'processBillingCountry'); // Checkout Edit Billing Address
		add_action('woocommerce_after_checkout_shipping_form', 'processShippingCountry'); // Checkout Edit Billing Address
		add_action( 'woocommerce_after_edit_address_form_billing', 'processBillingCountry'); // My Account - Edit Billing Address
		add_action( 'woocommerce_after_edit_address_form_shipping', 'processShippingCountry'); // My Account - Edit Shipping Address
	}
}


// Override all (both shipping and billing) text fields' placeholders
// NOTE: Still can't find a way to change text placeholder dynamically. Ideally we want to change it to autocomplete for supported countries
//       and use the defaults if the country isn't supported
add_filter( 'woocommerce_default_address_fields', 'custom_override_default_checkout_fields', 10, 1 );
function custom_override_default_checkout_fields( $address_fields ) {
	$hpwWooOptions = get_option("HPWAddrLookup_Woo");
	if($hpwWooOptions['solution'] == "AutoComplete") {
		$address_fields['address_1']['placeholder'] = __( 'Please type your address here', 'woocommerce' );
		$address_fields['address_2']['placeholder'] = __( '', 'woocommerce' );
	} else if($hpwWooOptions['solution'] == "Address Lookup") {
		$address_fields['address_1']['placeholder'] = __( 'Please use the Find Address above', 'woocommerce' );
		$address_fields['address_2']['placeholder'] = __( '', 'woocommerce' );
	}

    return $address_fields;
}
