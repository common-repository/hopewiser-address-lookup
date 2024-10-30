<?php

// Print Hopewiser Address Lookup
function HopewiserAddrLookup_printAddressLookup($atts = []) {
	$returnString = "";
	if(empty($atts)) {
		print "ERROR: Please define a Country to match on your shortcode.<br/><br/>";
		return;
	}
	$hpwOptions = get_option('HPWAddrLookup_GeneralSettings'); // Get all the saved options

	$atts = array_change_key_case((array)$atts, CASE_LOWER);
	$country = strtoupper($atts['country']);
	$isCountrySupported = false;

	$mainDataset = HPWAddrLookup_getMainDataset($country);
	if($mainDataset)
		$isCountrySupported = true;
	else
		return "ERROR: Country code `$country` is not supported. Please check the Administration settings for supported countries.<br/><br/>";

	$httpServer = HPWAddrLookup_getServer();	
	if($isCountrySupported == true) {

$returnString .= '
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

';
		
$returnString .= '
  			<script>
			function input_onkeypress(e) {
    			var ch = e.charCode ? e.charCode : e.keyCode;
    			document.getElementById("btnFindAddress").disabled = false;
    				if(ch == 0xD)
      					document.getElementById("btnFindAddress").click();
  			}

			function btnFindAddress_onclick() {				
				HPW.FindAddress({
      				auth: "'.$hpwOptions["authtoken"].'",					
      				dataset: "'.$mainDataset.'",
					server: "'.$httpServer.'",
      				input: "input",  // required
      				output: {
        				line1:    "address1",     // required
        				line2:    "address2",     // required
        				line3:    "address3",     // optional
        				line4:    "address4",     // optional
        				town:     "city",         // required
        				county:   "state",        // optional
        				postcode: "postcode",     // required
        				country:  "country"       // optional
      				},
      				ReserveOrganisationLine: "AsRequired",
      				IncludeCounty: "AsRequired",
      				TownFormat: "Uppercase",
      				Debug: false
    			});
			}
			</script>';
	}

$returnString .= '
<p>
<div>
  <table>
    <tr>
    <td><label for="input">Search&nbsp;For:</label></td>
    <td><input id="input" maxlength="50" onkeypress="input_onkeypress(event)" /></td>
    <td><button type="button" id="btnFindAddress" style="width:8em" disabled="disabled" data-toggle="modal" data-target="#hpwModal" data-backdrop="static" onclick="btnFindAddress_onclick()">Find Address</button></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td><label for="address1">Address:</label></td>
    <td><input id="address1" maxlength="56" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input id="address2" maxlength="56" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input id="address3" maxlength="56" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input id="address4" maxlength="56" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><label for="city">Town/City:</label></td>
    <td><input id="city" maxlength="56" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><label for="state">County:</label></td>
    <td><input id="state" maxlength="56" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><label for="postcode">Postcode:</label></td>
    <td><input id="postcode" maxlength="12" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><label for="country">Country:</label></td>
    <td><input id="country" maxlength="56" /></td>
    <td>&nbsp;</td>
  </tr>
  </table>
</div></p>';


	return $returnString;


}
add_shortcode('hpw-addrlookup', 'HopewiserAddrLookup_printAddressLookup');
