<?php

HPWAddrLookup_displayFormWooCommerce();
function HPWAddrLookup_displayFormWooCommerce() {
  $hpwAddrLookup_WooOptions = get_option("HPWAddrLookup_Woo");
?>

  <form method="post" action="options.php">
      <?php settings_fields( 'HPWAddrLookup_WooCommerceGroup' ); ?>
      <br/>
      <hr/><br/>


  <table cellpadding="4px">
  <tr valign="top">
		<th scope="row"><label for="HPWAddrLookup_Woo[enable]">Enable WooCommerce Integration </label></th>
    	<td>


		<?php
			if(isset($hpwAddrLookup_WooOptions['enable']))
    			print '<input type="checkbox" id="HPWAddrLookup_Woo" name="HPWAddrLookup_Woo[enable]" value="1"' . checked( 1, $hpwAddrLookup_WooOptions['enable'], false ) . '/> ';
			else print  '<input type="checkbox" id="HPWAddrLookup_Woo" name="HPWAddrLookup_Woo[enable]" value="1" />';

		?>

		</td>
  </tr>
  </table>
  <br/>
  <hr/>
  <br/>
  <table cellpadding="4px">
  <tr valign="top">
		<th scope="row"><label for="HPWAddrLookup_Woo[solution]">Address Matching Solution </label></th>
		<td>
			<select id="HPWAddrLookup_Woo" name="HPWAddrLookup_Woo[solution]">
			<option id="HPWAddrLookup_Woo" value="AutoComplete"
							<?php
								if(isset($hpwAddrLookup_WooOptions['solution']))
									if($hpwAddrLookup_WooOptions['solution'] == "AutoComplete") echo 'selected';
							?>
							>AutoComplete</option>
			<option id="HPWAddrLookup_Woo" value="Address Lookup"
							<?php if(isset($hpwAddrLookup_WooOptions['solution']))
									if($hpwAddrLookup_WooOptions['solution']== "Address Lookup") echo 'selected';
							?>
							>Address Lookup</option>
			<option id="HPWAddrLookup_Woo" value="International Address Lookup"
							<?php if(isset($hpwAddrLookup_WooOptions['solution']))
									if($hpwAddrLookup_WooOptions['solution']== "International Address Lookup") echo 'selected';
							?>
							>International Address Lookup</option>
			</select>
<?php
	print '</td></tr><tr><td>';
	submit_button();
	print '</td><td>';
	print '</form></td></tr></table>';
}
