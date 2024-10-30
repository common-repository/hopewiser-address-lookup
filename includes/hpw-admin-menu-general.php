<?php

HPWAddrLookupPlugin_options_page();


function HPWAddrLookupPlugin_options_page() {
// ------------------------------------
//
	// Sanitising Inputs
	if(isset($_POST['username']))
		$username = sanitize_text_field ($_POST['username']);
	if(isset($_POST['password']))
		$password = sanitize_text_field ($_POST['password']);

  ?>
<div>
	<form method="post" action="">
	<table>
	<tr valign="top">
  	<th scope="row"><label>Token Username</label></th>
  		<td><input type="text" size="70" id="username" name="username" value="<?php if (isset($username)) echo esc_attr($username);
				  else echo "" ?>" />
		</td>
  	</tr>

  	<tr valign="top">
  	<th scope="row"><label>Token Password</label></th>
  	<td><input type="password" size="70" id="password" name="password" value="<?php if (isset($password)) echo esc_attr($password); else echo "" ?>" />
		 <p class="description">
	  	Please enter your Hopewiser Token's username & password to update your settings</p>
  	</td>
  </tr>

  <?php
	print '<tr><td>&nbsp;</td><td>';
	submit_button('Load Settings', 'primary large', 'primary', false);
	print '</td></tr></table></form>';

	// If user has not submitted username/password,
	// don't print the rest of the settings and options like MAFs, etc
	if(!$_POST) {
		return;
	}
  ?>

  <?php
	$selectedAU_MAF = ""; // Australian MAF
	$selectedUK_MAF = ""; // UK MAF
	$selectedNZ_MAF = ""; // New Zealand MAF
	$selectedIRL_MAF = ""; // Ireland MAF
	$selectedIntl_MAF = ""; // International MAF

	$hpwOptions = get_option("HPWAddrLookup_GeneralSettings");
	$httpServer = HPWAddrLookup_getServer();

	if(isset($hpwOptions["server"])) {		
		if(isset($hpwOptions["aumaf"]))
			$selectedAU_MAF = get_option('HPWAddrLookup_GeneralSettings')["aumaf"];
		if(isset($hpwOptions["ukmaf"]))
			$selectedUK_MAF = get_option('HPWAddrLookup_GeneralSettings')["ukmaf"];
		if(isset($hpwOptions["nzmaf"]))
			$selectedNZ_MAF = get_option('HPWAddrLookup_GeneralSettings')["nzmaf"];
		if(isset($hpwOptions["irlmaf"]))
			$selectedIRL_MAF = get_option('HPWAddrLookup_GeneralSettings')["irlmaf"];
		if(isset($hpwOptions["intlmaf"]))
			$selectedIntl_MAF = get_option('HPWAddrLookup_GeneralSettings')["intlmaf"];
	}
	$service_url = $httpServer."/atlaslive/json";

	$args = array(
    	'headers' => array(
        	'Authorization' => 'Basic ' . base64_encode($username.':'.$password)
    	)
	);
	
	$request = wp_remote_get( $service_url, $args );	
	if( is_wp_error( $request ) ) {		
		print 'There is an error when trying to connect to the API server<br/>';
		echo $request->get_error_message();
		return false;
	}
	$body = wp_remote_retrieve_body($request);
	$data = json_decode($body, true);

    // If authentication successful, get the available MAFs for this account
    if($data["Status"] != "OK") {
        print "<br/><B><font color='red'>ERROR: ".$data["StatusDetails"]["Description"]." </font></b>";
		return;
    } else {

?>

<form method="post" action="options.php">
  <?php settings_fields( 'HPWGeneralGroup' ); ?>

  <br/>
  <hr/><br/>
  <h3>Address Lookup Settings</h3>

  <table cellpadding="4px">
  <tr valign="top">
		<th scope="row"><label for="Server Selection">Server Selection: </label></th>
    	<td>
			<select id="HPWGeneral_server" name="HPWAddrLookup_GeneralSettings[server]">
			<option id="HPWGeneral_server" value="Australia"
							<?php
								if(isset($hpwOptions['server']))
									if($hpwOptions['server'] == "Australia") echo 'selected';
							?>
							>Australia</option>
			<option id="HPWGeneral_server" value="UK"
							<?php if(isset($hpwOptions['server']))
									if($hpwOptions['server']== "UK") echo 'selected';
							?>
							>United Kingdom</option>
			</select>
			<p>Please select the one closest to your location</p>
		</td>
  </tr>

  <tr valign="top">

  <td><input type="hidden" size="70" id="hpw_authtoken" name="HPWAddrLookup_GeneralSettings[authtoken]" value="" />
		<script>
			var authcode = base64Encode("<?php echo $username.":".$password; ?>");
   			document.getElementById("hpw_authtoken").value = authcode;
			document.getElementById("hpw_authtoken").innerHTML = authcode;
		</script>
  </td>
  </tr>

<?php
		print '<tr valign="top"><th scope="row"><label>Available MAFs</label></th>
			<td>';
		hpwAddrLookup_print_MAF_Country($data["Results"]["MAFs"]);
    }

	// If user hasn't selected and saved a MAF yet, print a text reminder
	if($selectedAU_MAF == '' && $selectedUK_MAF == '' && $selectedNZ_MAF == '' && $selectedIRL_MAF == '' && $selectedIntl_MAF == '') {
		print '<p class="description">Please select a valid MAF to use and save changes.</p>';
	}
?>

<?php
	print '</td></tr><tr><td>';
	submit_button();
	print '</td><td>';
	print '</form></td></tr></table>';

 ?>

</div>
<?php
}


// Print dropdown of available MAFs, and auto-select the saved MAF from before, if any
function hpwAddrLookup_print_MAF_Country($mafObject) {
// --------------------------------------------------------------
	$listMAFs = $mafObject;
	$au_mafs = [];
	$uk_mafs = [];
	$nz_mafs = [];
	$irl_mafs = [];
	$intl_mafs = [];

    foreach ($listMAFs as $maf) {
		$mafBreakdowns = explode("-", $maf["ID"]);
		if($mafBreakdowns[0] == "aus") {
			array_push($au_mafs, $maf["ID"]);
		} else if($mafBreakdowns[0] == "uk") {
			array_push($uk_mafs, $maf["ID"]);
		} else if($mafBreakdowns[0] == "nz") {
			array_push($nz_mafs, $maf["ID"]);
		} else if($mafBreakdowns[0] == "irl") {
			array_push($irl_mafs, $maf["ID"]);
		} else if($mafBreakdowns[0] == "international") {
			array_push($intl_mafs, $maf["ID"]);
		}
	}

	print '<table>';
	// Print dropdown on the country only if there is a MAF of the country set on the account
	if($uk_mafs) hpwAddrLookup_htmlCountryMafDropdown("uk", "United Kingdom", $uk_mafs);
	if($au_mafs) hpwAddrLookup_htmlCountryMafDropdown("au", "Australia", $au_mafs);
	if($nz_mafs) hpwAddrLookup_htmlCountryMafDropdown("nz", "New Zealand", $nz_mafs);
	if($irl_mafs) hpwAddrLookup_htmlCountryMafDropdown("irl", "Ireland", $irl_mafs);
	if($intl_mafs) hpwAddrLookup_htmlCountryMafDropdown("intl", "International", $intl_mafs);

	print '</table>';
}

// Print all countries & associated MAF files of the account into dropdown
// e.g: Australia [aus-psma-gnaf]
function hpwAddrLookup_htmlCountryMafDropdown($mafCountryCode, $mafCountry, $mafArray){
// ---------------------------------------------------------------------
	$hpwOptions = get_option("HPWAddrLookup_GeneralSettings");

	print '<tr valign="top"><td border="0" width="120px">&nbsp;'.esc_html($mafCountry).'</td><td>';
	print '<select id="hpw_maflists" name="HPWAddrLookup_GeneralSettings['.esc_html($mafCountryCode).'maf]">';
	foreach ($mafArray as $maf) {
		// Display the saved MAF (if already selected & saved by user) on the dropdown
		// i.e has the item selected on the dropdown
		if(isset($hpwOptions[$mafCountryCode."maf"])) {
			if($hpwOptions[$mafCountryCode."maf"] == $maf) {
				echo '<option id="hpw_maflists" value="'.esc_attr($maf). '"     selected>'.esc_html($maf).'</option>';
			} else {
				echo '<option value="'. esc_attr($maf). '">'.esc_html($maf).'</option>';
			}
		} else {
			echo '<option value="'. esc_attr($maf). '">'.esc_html($maf).'</option>';
		}
	}
	print '</select></tr>';
}
