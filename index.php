<?php

/**
 * -----------------------------------------------------------------------------
 * Plugin Name: Username Protection
 * Description: Prevent anonymous users from listing usernames via the REST API.
 * Version: 0.1.1
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
	 * Type of error notice.
	 *
	 * This value is used as the code for the WP_Error returned if user is anonymous.
	 *
	 * @var string Error code to display when API access is blocked.
	 */
	public $error_code;

	/**
	 * Text of error notice.
	 *
	 * This value is used for the error notice if user is anonymous.
	 *
	 * @var string Error message to display when API access is blocked.
	 */
	public $error_text;

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
		// Set WP_Error code and message for forbidden requests.
		$this->error_code = 'rest_forbidden';
		$this->error_text = __( 'Sorry, you are not allowed to do that.', 'username-protection' );

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
		add_filter(
			'rest_pre_dispatch',
			[$this, 'prevent_anonymous_username_enumeration'],
			10,
			3
		);
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
	 * @param mixed           $result  Response that overrides the endpoint
	 *                                 return value, if present.
	 * @param WP_REST_Server  $server  API server instance.
	 * @param WP_REST_Request $request Request used to generate the response.
	 *
	 * @return WP_Error|null
	 */
	public function prevent_anonymous_username_enumeration($result, $server, $request) {

		// If user is admin, no need to block access.
		if (current_user_can('manage_options')) {
			return $result;
		}

		// If user is logged in, *probably* no need to block access.
		if (is_user_logged_in()) {
			return $result;
		}

		// Is this the posts or users endpoint?
		if (strstr($request->get_route(), 'wp/v2/posts')) {
			// If _embed argument is absent, no need to block access.
			if (!isset($_GET['_embed'])) {
				return $result;
			}
		} else if (!strstr($request->get_route(), 'wp/v2/users')) {
			// ...also not the users endpoint, so no need to block access.
			return $result;
		}

		// If here, block access. No REST for the wicked!
		return new \WP_Error(
			$this->error_code,
			$this->error_text,
			['status' => rest_authorization_required_code()]
		);
	}

}

// Instantiate the object.
new UsernameProtection;
