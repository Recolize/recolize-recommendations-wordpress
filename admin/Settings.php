<?php
/**
 * Recolize Wordpress Admin Settings
 *
 * @section LICENSE
 * This source file is subject to the GNU General Public License Version 3 (GPLv3).
 *
 * @category Recolize
 * @package Recolize_RecommendationEngine
 * @author Recolize GmbH <service@recolize.com>
 * @copyright 2015 Recolize GmbH (http://www.recolize.com)
 * @license http://opensource.org/licenses/GPL-3.0 GNU General Public License Version 3 (GPLv3).
 */
class Recolize_Admin_Settings
{
    /**
     * The name of the WordPress option that contains all Recolize settings.
     *
     * @var string
     */
    const OPTION_NAMESPACE = 'RecolizeSettings';

    /**
     * The name of the Recolize option for the JavaScript snippet.
     *
     * @var string
     */
    const OPTION_NAME_JAVASCRIPT_SNIPPET = 'RecolizeJavaScriptSnippet';

    /**
     * Add Recolize Admin menu point and options page sections.
     *
     * @return Recolize_Admin_Settings
     */
    public function initialize()
    {
        add_action('admin_menu', array($this, 'addMenu'));
        add_action('admin_init', array($this, 'addSections'));

        return $this;
    }

    /**
     * Add the Recolize Admin menu point.
     *
     * @return Recolize_Admin_Settings
     */
    public function addMenu()
    {
        add_options_page('Recolize Recommendation Engine', 'Recolize', 'manage_options', 'recolize', array($this, 'renderOptionsPage'));
        return $this;
    }

    /**
     * Add the Recolize options sections.
     *
     * @return Recolize_Admin_Settings
     */
    public function addSections()
    {
        register_setting('pluginPage', self::OPTION_NAMESPACE);

        add_settings_section(
            'Recolize_pluginPage_section',
            '<img src="http://www.recolize.com/media/wysiwyg/recolize_logo_200.png" />',
            array($this, 'renderSettingsHeader'),
            'pluginPage'
        );

        add_settings_field(
            self::OPTION_NAME_JAVASCRIPT_SNIPPET,
            __('JavaScript Snippet Code'),
            array($this, 'renderJavaScriptSnippetTextarea'),
            'pluginPage',
            'Recolize_pluginPage_section'
        );

        return $this;
    }

    /**
     * Render the textarea for the Recolize JavaScript snippet option.
     *
     * @return Recolize_Admin_Settings
     */
    public function renderJavaScriptSnippetTextarea()
    {
        $options = get_option(self::OPTION_NAMESPACE);

        $html = sprintf(
            '<textarea cols="70" rows="10" name="%s[%s]">%s</textarea>',
            self::OPTION_NAMESPACE,
            self::OPTION_NAME_JAVASCRIPT_SNIPPET,
            $options[self::OPTION_NAME_JAVASCRIPT_SNIPPET]
        );
        echo $html;

        return $this;
    }

    /**
     * Render the Recolize settings header.
     *
     * @return Recolize_Admin_Settings
     */
    public function renderSettingsHeader()
    {
        echo __('<strong>Already registered for Recolize?</strong><br />Otherwise please create an account for free with just your email address at <a href="http://www.recolize.com/en/register?utm_source=wordpress-plugin-admin-settings&utm_medium=web&utm_campaign=WordPress Admin" target="_blank">our website</a>.');
        return $this;
    }

    /**
     * Render the Recolize options page.
     *
     * @return Recolize_Admin_Settings
     */
    public function renderOptionsPage()
    {
        echo '<form action="' . admin_url('options.php') . '" method="post"><h2>Recolize Recommendation Engine</h2>';
        settings_fields('pluginPage');
        do_settings_sections('pluginPage');
        submit_button();
        echo '</form>';

        return $this;
    }

    /**
     * Return the Recolize JavaScript snippet.
     *
     * @return string
     */
    public static function getJavaScriptSnippet()
    {
        $options = get_option(self::OPTION_NAMESPACE);
        return $options[self::OPTION_NAME_JAVASCRIPT_SNIPPET];
    }
}