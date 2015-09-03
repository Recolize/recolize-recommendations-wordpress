<?php
/**
 * @package Recolize_RecommendationEngine
 * @version 1.0
 */
/*
Plugin Name: Recolize Recommendation Engine
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. Whe
n activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Recolize GmbH <service@recolize.com>
Version: 1.0
Author URI: http://www.recolize.com/
*/

// Make sure we don't expose any info if called directly
if (function_exists('add_action') === false) {
	echo 'Hi there! I\'m just a plugin, not much I can do when called directly.';
	exit;
}

/// Add Recolize JS Snippet to every page
function addRecolizeJavaScriptSnippet()
{
    $options = get_option('RecolizeSettings');
    echo $options['Recolize_textarea_field_0'];
}

add_action('wp_head', 'addRecolizeJavaScriptSnippet');

/// Recolize Admin Settings
add_action('admin_menu', 'addRecolizeAdminMenu');
add_action('admin_init', 'initRecolizeSettings');

function addRecolizeAdminMenu()
{
	add_options_page('Recolize Recommendation Engine', 'Recolize', 'manage_options', 'recolize', 'renderRecolizeOptionsPage');
}

function initRecolizeSettings()
{
	register_setting('pluginPage', 'RecolizeSettings');

	add_settings_section(
		'Recolize_pluginPage_section',
		'<img src="http://www.recolize.com/skin/frontend/besugre/default/images/background_images/recolize_logo.png" />',
		'callbackRecolizeSettingsSection',
		'pluginPage'
	);

	add_settings_field(
		'Recolize_textarea_field_0',
		__('JS Snippet Code'),
		'renderRecolizeJavaScriptSnippetTextareaField',
		'pluginPage',
		'Recolize_pluginPage_section'
	);
}


function renderRecolizeJavaScriptSnippetTextareaField()
{
	$options = get_option('RecolizeSettings');
	?>
	<textarea cols='40' rows='5' name='RecolizeSettings[Recolize_textarea_field_0]'>
		<?php echo $options['Recolize_textarea_field_0']; ?>
 	</textarea>
	<?php
}


function callbackRecolizeSettingsSection()
{
	echo __('Already registered for Recolize? Otherwise please create an account for free at <a href="#" target="_blank">our website</a>.');
}


function renderRecolizeOptionsPage()
{
	?>
	<form action='options.php' method='post'>

		<h2>Recolize Recommendation Engine</h2>

		<?php
		settings_fields('pluginPage');
		do_settings_sections('pluginPage');
		submit_button();
		?>

	</form>
	<?php
}