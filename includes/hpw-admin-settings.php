<?php
// Register options into the database (under WordPress options table)
// These same naming options shall be used for all Hopewiser plugins in the future
// so we don't have to add different naming with same values in the same WordPress database
function HPWAddrLookupPlugin_register_settings() {
// ----------------------------------------
   register_setting( 'HPWGeneralGroup', 'HPWAddrLookup_GeneralSettings', 'HPWPlugin_callback' );
   register_setting( 'HPWAddrLookup_WooCommerceGroup', 'HPWAddrLookup_Woo', 'HPWPlugin_callback' );
	register_setting( 'HPWAddrLookup_FormsGroup', 'HPWAddrLookup_Forms', 'HPWPlugin_callback' );

   wp_enqueue_script( 'base64encoder', plugin_dir_url( __FILE__ ) . 'js2/authcode.min.js', array( 'jquery' ) );
}
add_action( 'admin_init', 'HPWAddrLookupPlugin_register_settings' );



function HPWAddrLookupPlugin_register_options_page() {
  add_options_page('Hopewiser Settings', 'Hopewiser', 'manage_options', 'HPWPlugin', 'hpwaddrlookup_admin_settings_page');
}
add_action('admin_menu', 'HPWAddrLookupPlugin_register_options_page');

// Print the Root Page, with Tab menus
function hpwaddrlookup_admin_settings_page(){
	print '<h1>Hopewiser Address Lookup</h1><br/>';
	global $hpwaddrlookup_active_tab; // to store which tab is currently active
	
	if(isset($_GET['tab'])) {
		$tabOption = sanitize_text_field($_GET['tab']);
	} else {
		$tabOption = 'general';
	}
	
	$hpwaddrlookup_active_tab = $tabOption;
	?>

	<h2 class="nav-tab-wrapper">
	<?php
		do_action( 'hpwaddrlookup_settings_tab' );
	?>
	</h2>
	<?php
		do_action( 'hpwaddrlookup_settings_content' );
}

// ---------------------------------------------------------------
// Ganeral Settings
// ---------------------------------------------------------------
// Add General Settings Tab
add_action( 'hpwaddrlookup_settings_tab', 'hpwaddrlookup_general_tab', 1 );
function hpwaddrlookup_general_tab(){
	global $hpwaddrlookup_active_tab; ?>
	<a class="nav-tab <?php echo $hpwaddrlookup_active_tab == 'welcome' || '' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'options-general.php?page=HPWPlugin&tab=general' ); ?>"><?php _e( 'General', 'sd' ); ?> </a>
	<?php
}

// Tab: General Settings Page
add_action( 'hpwaddrlookup_settings_content', 'hpwaddrlookup_general_render_options_page' );
function hpwaddrlookup_general_render_options_page() {
	global $hpwaddrlookup_active_tab;
	if ( '' || 'general' != $hpwaddrlookup_active_tab )
		return;
	?>
	<?php
	  print "<br/><br/>";
      settings_fields( 'HPWPlugin_options_group' );
      require_once plugin_dir_path(__FILE__) . 'hpw-admin-menu-general.php';
}
// ---------------------------------------------------------------

// Check to see if this is the first time the Admin visits the page.
// This is done by checking if there is a MAF setting, etc being saved previously. If it has, then show all other options
// If no setting is saved yet, then we hide other options until the admin logins and saves the MAF setups, etc.
$hpwAddrLookup_options = get_option('HPWAddrLookup_GeneralSettings'); // Get all the saved options
if(!$hpwAddrLookup_options) return; // Stop and don't show other options

// ---------------------------------------------------------------
// WooCommerce
// ---------------------------------------------------------------
// Add WooCommerce Tab
// ---------------------------------------------------------------
add_action( 'hpwaddrlookup_settings_tab', 'hpwaddrlookup_woocommerce_tab', 2 );
function hpwaddrlookup_woocommerce_tab(){
	global $hpwaddrlookup_active_tab; ?>
	<a class="nav-tab <?php echo $hpwaddrlookup_active_tab == 'woocommerce' || '' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'options-general.php?page=HPWPlugin&tab=woocommerce' ); ?>"><?php _e( 'WooCommerce', 'sd' ); ?> </a>
	<?php
}

// Tab: WooCommerce Page
add_action( 'hpwaddrlookup_settings_content', 'hpwaddrlookup_woocommerce_render_options_page' );
function hpwaddrlookup_woocommerce_render_options_page() {
	global $hpwaddrlookup_active_tab;
	if ( '' || 'woocommerce' != $hpwaddrlookup_active_tab )
		return;
	?>

	<?php
      require_once plugin_dir_path(__FILE__) . 'hpw-admin-menu-woocommerce.php';
}
// ---------------------------------------------------------------


// ---------------------------------------------------------------
// Shortcodes
// ---------------------------------------------------------------
// Add Shortcode Tab, only if admin has saved MAF settings
// --------------------------------------------------------------- 
add_action( 'hpwaddrlookup_settings_tab', 'hpwaddrlookup_shortcode_tab', 3 );
function hpwaddrlookup_shortcode_tab(){
	global $hpwaddrlookup_active_tab; ?>
	<a class="nav-tab <?php echo $hpwaddrlookup_active_tab == 'shortcode' || '' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'options-general.php?page=HPWPlugin&tab=shortcode' ); ?>"><?php _e( 'Shortcode', 'sd' ); ?> </a>
	<?php
}

// Tab: Shortcode Page
add_action( 'hpwaddrlookup_settings_content', 'hpwaddrlookup_shortcode_render_options_page' );
function hpwaddrlookup_shortcode_render_options_page() {
	global $hpwaddrlookup_active_tab;
	if ( '' || 'shortcode' != $hpwaddrlookup_active_tab )
		return;
	?>

	<?php
      require_once plugin_dir_path(__FILE__) . 'hpw-admin-menu-shortcode.php';
}
// ---------------------------------------------------------------


// ---------------------------------------------------------------
// Forms Integration
// --------------------------------------------------------------- 
// Add Forms Tab, only if admin has saved MAF settings
// ---------------------------------------------------------------
add_action( 'hpwaddrlookup_settings_tab', 'hpwaddrlookup_forms_tab', 4 );
function hpwaddrlookup_forms_tab(){
	global $hpwaddrlookup_active_tab; ?>
	<a class="nav-tab <?php echo $hpwaddrlookup_active_tab == 'forms' || '' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'options-general.php?page=HPWPlugin&tab=forms' ); ?>"><?php _e( 'Forms', 'sd' ); ?> </a>
	<?php
}

// Tab: Forms Page
add_action( 'hpwaddrlookup_settings_content', 'hpwaddrlookup_forms_render_options_page' );
function hpwaddrlookup_forms_render_options_page() {
	global $hpwaddrlookup_active_tab;
	if ( '' || 'forms' != $hpwaddrlookup_active_tab )
		return;
	?>

	<?php
      require_once plugin_dir_path(__FILE__) . 'hpw-admin-menu-forms.php';
}
// --------------------------------------------------------------- 
