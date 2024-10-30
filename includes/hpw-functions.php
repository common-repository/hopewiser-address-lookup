<?php

// Return the server set by the admin
function HPWAddrLookup_getServer() {
	$hpwOptions = get_option('HPWAddrLookup_GeneralSettings'); // Get all the saved options

	if(isset($hpwOptions["server"])) {
	   	if($hpwOptions["server"] == "Australia")
   			$httpServer = "https://cloud.hopewiser.com.au";
		else
			$httpServer = "https://cloud.hopewiser.com";
	} else {
		$httpServer = "https://cloud.hopewiser.com";
	}

	return $httpServer;
}

// Return the mainDataset (MAF files to be used)
function HPWAddrLookup_getMainDataset($country) {
	$hpwOptions = get_option('HPWAddrLookup_GeneralSettings'); // Get all the saved options
	$hpwFormOptions = get_option('HPWAddrLookup_Forms');
	$mainDataset = "";

	if($country == "AU") {
		if(isset($hpwOptions['aumaf']) && $hpwOptions['aumaf'] != "") {
			$mainDataset = get_option("HPWAddrLookup_GeneralSettings")['aumaf'];
		}
	} else if($country == "UK") {
		if(isset($hpwOptions['ukmaf']) && $hpwOptions['ukmaf'] != "") {
			$mainDataset = get_option("HPWAddrLookup_GeneralSettings")['ukmaf'];
		}
	} else if($country == "IE") {
		if(isset($hpwOptions['irlmaf']) && $hpwOptions['irlmaf'] != "") {
			$mainDataset = get_option("HPWAddrLookup_GeneralSettings")['irlmaf'];
		}
	} else if($country == "NZ") {
		if(isset($hpwOptions['nzmaf']) && $hpwOptions['nzmaf'] != "") {
			$mainDataset = get_option("HPWAddrLookup_GeneralSettings")['nzmaf'];
		}
	}

	return $mainDataset;
}

?>
