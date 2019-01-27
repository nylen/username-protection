<?php

/**
 * -----------------------------------------------------------------------------
 * Plugin Name: Username Protection
 * Description: Prevent anonymous users from listing usernames via the REST API.
 * Version: 0.1.0
 * Author: Code Potent
 * Author URI: https://codepotent.com
 * Plugin URI: https://github.com/johnalarcon/username-protection
 * Text Domain: username-proctection
 * Domain Path: /languages
 * -----------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/gpl-2.0.txt.
 * -----------------------------------------------------------------------------
 * Copyright © 2019 - CodePotent
 * -----------------------------------------------------------------------------
 *           ____          _      ____       _             _
 *          / ___|___   __| | ___|  _ \ ___ | |_ ___ _ __ | |_
 *         | |   / _ \ / _` |/ _ \ |_) / _ \| __/ _ \ '_ \| __|
 *         | |__| (_) | (_| |  __/  __/ (_) | ||  __/ | | | |_
 *          \____\___/ \__,_|\___|_|   \___/ \__\___|_| |_|\__|.com
 *
 * -----------------------------------------------------------------------------
 */

// Declare the namespace.
namespace CodePotent\UsernameProtection;

// Prevent direct access.
if (!defined('ABSPATH')) {
	die();
}

/**
 * Username Protection
 *
 * A class to prevent anonymous enumeration of usernames via the REST API. Given
 * the simplicity of this plugin, OOP isn't truly warranted; it was used here to
 * show a simple demonstration of how to use a filter from within the context of
 * a class; see the init() method.
 *
 * @author John Alarcon
 *
 * @since 0.1.0
 */
class UsernameProtection {

	/**
	 * Type of notice.
	 *
	 * This value is used as the key for the error text if user is anonymous.
	 *
	 * @var string Array key for error message.
	 */
	public $message_type;

	/**
	 * Text of notice.
	 *
	 * This value is used for the error notice if user is anonymous.
	 *
	 * @var string Error message to display when API access is blocked.
	 */
	public $message_text;

	/**
	 * A simple constructor fights gas and bloating!
	 *
	 * @author John Alarcon
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function __construct() {

		// Set notice key.
		$this->message_type = __('notice', 'username-protection');

		// Set notice text.
		$this->message_text = __('authentication required', 'username-protection');

		// Initialize the plugin.
		$this->init();

	}

	/**
	 * Setup actions and filters.
	 *
	 * This method hooks the auth-verifying method into the system.
	 *
	 * @author John Alarcon
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function init() {

		// Hook the auth-checking method into the system.
		add_filter('rest_authentication_errors', [$this, 'prevent_anonymous_username_enumeration']);

	}

	/**
	 * Prevent anonymous access to usernames.
	 *
	 * This method runs a series of successive checks to find out whether or not
	 * the user can access usernames via the REST API. If so, the method returns
	 * early. If not, the method runs to completion and quits.
	 *
	 * @author John Alarcon
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function prevent_anonymous_username_enumeration() {

		// If user is admin, no need to block access.
		if (current_user_can('manage_options')) {
			return;
		}

		// If user is logged in, *probably* no need to block access.
		if (is_user_logged_in()) {
			return;
		}

		// If not a RESTful URL, no need to block access.
		if (!strstr($_SERVER['REQUEST_URI'], '/wp-json/')) {
			return;
		}

		// If not the endpoint that exposes usernames, no need to block access.
		if (!strstr($_SERVER['REQUEST_URI'], '/users')) {
			return;
		}

		// If here, block the user. No REST for the wicked!

		// JSONify the output message.
		$error = json_encode([$this->message_type => $this->message_text]);

		// Kill the script with fire.
		die($error);

	}

}

// Instantiate the object.
new UsernameProtection;