=== Hopewiser Address Lookup ===
Contributors: hopewiserwp
Plugin URI: https://www.hopewiser.com
Tags: hopewiser,wordpress,address,woocommerce,address validation,autocomplete,address lookup,address verification,postcode
Requires at least: 5.3
Tested up to: 6.1.1
Stable tag: trunk
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin integrates Hopewiser services including Address Lookup and AutoComplete in WordPress and WooCommerce.

== Description ==

Hopewiser’s Address Lookup integrates seamlessly into Wordpress using our quick and easy plugin.  Works with WooCommerce, Contact Form 7, and Gravity Forms.

Quickly autofill and capture validated addresses as they are entered into the system saving keystrokes and improving accuracy. Reduce the risk of people not going through with a sign up/registration process by increasing the speed and simplicity of the form completion.

In WordPress, you can simply add our shortcodes into any page or post to have our Address Lookup and AutoComplete solution integrated. 

The WooCommerce plugin enables you to autocomplete and match addresses in both the billing and shipping address sections. 

To use this functionality, you will need an Address Lookup service account and a ‘live’ click bundle. To buy clicks you will need to purchase from the Shop located on our website. Register/sign in to the website portal here https://www.hopewiser.com/myaccount/#path=myaccount:register. 
       
• No More Failed Deliveries
Accurate address validation using the best available data such as the Royal Mail PAF and the Australia Post's Postal Address File & GNAF will significantly reduce failed deliveries and customer complaints.  Even if you ship internationally using our international data.

• Great User Experience
With real time address lookup there will be an increase in the usability of the site leading to increased customer satisfaction and experience, boosting customer loyalty to your brand.

• Reduces Cart Abandonment
Most shoppers will abandon a cart if a checkout process is too complicated. Validating at the point of entry will reduce keystrokes, leading to a speedier and happier user experience.

• Reporting
This service is accessed via a simple to use Portal which includes a powerful Management Tool showing detailed reporting of usage.

== Installation ==

• Activate the plugin on your WordPress Administration Dashboard
• Go to Settings->Hopewiser
• Enter your token username and password from your Hopewiser account (obtained through Hopewiser website). You can register for a free trial at hopewiser.com (or hopewiser.com.au if you are based in Australia)
• Click Load Settings, then choose the Server closest to your location and also your MAFs to be used on the address validation

== Frequently Asked Questions ==

Q. How are we supported?
A. Full, free support is included with the purchase of every bundle.  If you have any issues please feel free to contact us at Support@hopewiser.com

Q. Which countries data can I use?
A. We support UK (Royal  Mail PAF), and Ireland (Eircode), Australia (Australia Post's Postal Address File & GNAF), New Zealand (NZ Post's GeoPAF). We also have an International Address Lookup dataset to cover the rest of the world.

Q. Do I need an account to use the service?
A. Whilst the plugin is free the address lookup service does require a Hopewiser Adddress Lookup account.  You can sign up for an account at https://www.hopewiser.com/myaccount/#path=myaccount:register and view our click bundle pricing.
    
Q. Can I get a test account?
A. Yes, you can sign up for a free test bundle https://www.hopewiser.com/myaccount/#path=myaccount:register. If you require more test clicks please get in touch at support@hopewiser.com or woocommerce@hopewiser.com.

Q. What is the difference between Autocomplete and Address Lookup?
A. With autocomplete the address fills in as you type, allowing you to choose a full address from just a few keystrokes. Address Lookup allows you to input a partial address or postcode and select the address from a drop down list within the postcode or area.


== Screenshots ==

1. Hopewiser account login screen on Administration page
2. WooCommerce integration screen on Administration page
3. AutoComplete addresses on WooCommerce Checkout page
4. Address Lookup solution on WooCommerce Checkout page
5. Using shortcodes to add address form with validation
6. Forms integration screen on Administration page

== Changelog ==
= 2.04 =
* AutoComplete now also works on WooCommerce Admin Order page (both billing and shipping) for adding orders manually or editing current orders
* Fix admin error message "Trying to get property 'post_content'" appearing when Contact Form 7 integration is enabled

= 2.03 =
* Refresh Checkout Cart after selecting an address

= 2.02 = 
* Fix an invalid URL error after entering token username/password on the first installation

= 2.01 =
* Fix Address Capture becoming unavailable error due to token becoming empty after saving changes from the admin settings

= 2.00 = 
* Removed easyXDM script
* Minified all JavaScript and CSS files for smaller plugin size

= 1.10 = 
* Fixed address line 5 & 6 not appearing on AutoComplete forms & shortcode
* Fixed Gravity Forms integration not working properly with Gravity Forms' Gutenberg block
* Fixed pop up error message not being displayed properly when having server connection or MAF issues
* Updated Gravity Forms integration's instructions on the admin section to match Gravity Forms' v2.5 changes

= 1.09 =
* Compatible with WordPress 5.7
* Updated plugin & author URL

= 1.08 =
* Fix empty Country dropdown when there is only one country allowed for shipping on the WooCommerce store
* Fix JavaScript error 'jQuery(...).autocomplete(...).data(...) is undefined' on some themes
* Address Lookup text input field now follows the theme's CSS

= 1.07 = 
* Remove third party jQuery library files
* Remove json2 inclusion, references, and file
* Sanitise tab selection input under administration settings
* Update Bootstrap JS libraries to the latest (from v3.1.1 to v4.5.2)

= 1.06 = 
* Changed form cURL to WordPress HTTP API
* Fixed compatibility issue with using WordPress jQuery
* Sanitised all input and output
* Hide other admin settings until account is authenticated and the MAFs are set
* Fix address search not popping up on Address Lookup shortcode if WooCommerce integration is disabled

= 1.05 = 
* Add support for posts using Advanced Custom Fields (ACF) plugin
* Add AutoComplete support to Gravity Forms
* Minor performance fix on Contact Form 7 integration

= 1.04 =
* Changed WordPress required version from 5.4.2 to 5.3
* Fix contact form 7 integration not working on themes using custom post fields for the content

= 1.03 =
* New integration of Hopewiser AutoComplete with Contact Form 7 plugin
* Fix a bug where posts containing AutoComplete shortcode cannot be updated or saved
* Print an error message when AutoComplete country code is not supported
* Changed country code IR to IE for the correct representation of Ireland

= 1.02 =
* Hide Address Lookup input search and button if selected country MAF is not set or unavailable
* Remove "@" symbol on Address Lookup search result modal pop-up
* Now displays International MAF on the Admin settings if provisioned
* Add Hopewiser h4 css styling to modal pop-ups

= v1.01 =
Fix end of parsing errors on some server configuration when Address Lookup/International Address Lookup is chosen

= v1.00 = 
Initial Release
