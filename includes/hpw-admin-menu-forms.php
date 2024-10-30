<?php

HopewiserAddrLookup_displayFormAll();
function HopewiserAddrLookup_displayFormAll() {
  $hpwAddrLookup_FormOptions = get_option("HPWAddrLookup_Forms");
?>

  <form method="post" action="options.php">
      <?php settings_fields( 'HPWAddrLookup_FormsGroup' ); ?>
      <br/>


	  <table>
		<tr><td>Search Country</td>
	  	<td>
<?php
	  $hpwAddrLookup_Options = get_option("HPWAddrLookup_GeneralSettings");

	  print '<select id="HPWForm_country" name="HPWAddrLookup_Forms[country]">';
	  if(isset($hpwAddrLookup_Options["aumaf"]))
		  print '<option id="HPWForm_country" value="AU" ';
		  if(isset($hpwAddrLookup_FormOptions['country']))
		  	if($hpwAddrLookup_FormOptions['country'] == "AU") echo 'selected';
		  print '>Australia</option>';
	  if(isset($hpwAddrLookup_Options["ukmaf"]))
		  print '<option id="HPWForm_country" value="UK" ';
		  if(isset($hpwAddrLookup_FormOptions['country']))
		  	if($hpwAddrLookup_FormOptions['country'] == "UK") echo 'selected';
		  print '>United Kingdom</option>';
	  if(isset($hpwAddrLookup_Options["nzmaf"]))
		  print '<option id="HPWForm_country" value="NZ" ';
		  if(isset($hpwAddrLookup_FormOptions['country']))
		  	if($hpwAddrLookup_FormOptions['country'] == "NZ") echo 'selected';
		  print '>New Zealand</option>';
	  if(isset($hpwAddrLookup_Options["irlmaf"]))
		  print '<option id="HPWForm_country" value="IE" ';
		  if(isset($hpwAddrLookup_FormOptions['country']))
		  	if($hpwAddrLookup_FormOptions['country'] == "IE") echo 'selected';
		  print '>Ireland</option>';

?>
		 </select>
		 <br/>
	     </td></tr>
	  </table>
	  <p class="description">This Search Country option will activate Hopewiser's AutoComplete function for the selected Country</p>

  <table cellpadding="4px">
  <tr valign="top">
		<th scope="row" width="250px"><p align="left">
			<label for="HPWAddrLookup_Forms[contactForm7]">Enable Contact Forms 7 Integration </label></p></th>
    	<td valign="middle">

		<?php
			if(isset($hpwAddrLookup_FormOptions['contactForm7']))
    			print '<input type="checkbox" id="HPWAddrLookup_Forms_contactForm7" name="HPWAddrLookup_Forms[contactForm7]" value="1"' . checked( 1, $hpwAddrLookup_FormOptions['contactForm7'], false ) . '/> ';
			else print  '<input type="checkbox" id="HPWAddrLookup_Forms_contactForm7" name="HPWAddrLookup_Forms[contactForm7]" value="1" />';
?>
		</td>
  </tr>
  <tr style="height:15px;"> <td colspan="2"> When creating your Contact 7 forms, add a new textarea with id:addrBox, e.g:<br/><br/>
	  <b>
		&lt;label&gt; Your Address<br/>
		  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[textarea addrBox id:addrBox] &lt;/label&gt;</b><br/><br/>

		You can also format the height of the text area using Contact Form 7's formatting, e.g: <br/>
		<b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[textarea addrBox id:addrBox x2] </b> <br/><br/>
  </td></tr>

  <tr valign="top">
	 <th scope="row" width="250px"><p align="left">
			<label for="HPWAddrLookup_Forms[gravityForms]">Enable Gravity Forms Integration </label></p></th>
    	<td valign="middle">

		<?php
			if(isset($hpwAddrLookup_FormOptions['gravityForms']))
    			print '<input type="checkbox" id="HPWAddrLookup_Forms_gravityForms" name="HPWAddrLookup_Forms[gravityForms]" value="1"' . checked( 1, $hpwAddrLookup_FormOptions['gravityForms'], false ) . '/> ';
			else print  '<input type="checkbox" id="HPWAddrLookup_Forms_gravityForms" name="HPWAddrLookup_Forms[gravityForms]" value="1" />';
?>
		</td>
  </tr>
  <tr> <td colspan="2"> When creating your Gravity Forms, add a Paragraph Text and name the Field Label with anything you like. <br/>
	  On the Appearance Tab, add a Custom CSS Class "addrBox" (without the quotes).
	  <br/>

  </td></tr>
  <br/>
  <hr/>
  <br/>


<?php

	print '</table>';
	submit_button();
	print '</form>';

}
