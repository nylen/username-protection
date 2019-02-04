<?php
/**
 * Class RESTProtectionTest
 *
 * @package CodePotent\UsernameProtection
 */

/**
 * Sample test case.
 */
class RESTProtectionTest extends WP_UnitTestCase {
	public $server;
	public static $editor;

	/**
	 * Set up variables and state for all tests in this file.
	 */
	public static function setUpBeforeClass() {
		// Create a test user.
		self::$editor = wp_insert_user([
			'role'       => 'editor',
			'user_login' => 'test_editor',
			'user_pass'  => wp_generate_password(),
		]);
	}

	/**
	 * Set up variables and state for each test.
	 */
	public function setUp() {
		// Override the REST API server object with one designed for testing.
		$GLOBALS['wp_rest_server'] = $this->server = new Spy_REST_Server();
		// Register the ClassicPress built-in REST API endpoints.
		do_action( 'rest_api_init' );
		// Call any setUp() functions of parent test classes.
		parent::setUp();
	}

	/**
	 * Clean up variables and state after each test.
	 */
	public function tearDown() {
		// Reset the _embed REST API parameter.
		unset( $_GET['_embed'] );
		// Call any tearDown() functions of parent test classes.
		parent::tearDown();
	}

	/**
	 * Clean up after all the tests in this file have run.
	 */
	public static function tearDownAfterClass() {
		wp_delete_user(self::$editor);
		self::$editor = null;
	}

	/**
	 * Verify that unauthenticated access to the /wp/v2/users endpoint is blocked.
	 */
	public function test_get_users_unauthenticated() {
		$request = new WP_REST_Request('GET', '/wp/v2/users');
		$response = $this->server->dispatch($request);
		$this->assertTrue(is_wp_error($response));
		$this->assertEquals('rest_forbidden', $response->get_error_code());
	}

	/**
	 * Verify that unauthenticated access to a single user is blocked.
	 */
	public function test_get_user_unauthenticated() {
		$request = new WP_REST_Request('GET', '/wp/v2/users/' . self::$editor);
		$response = $this->server->dispatch($request);
		$this->assertTrue(is_wp_error($response));
		$this->assertEquals('rest_forbidden', $response->get_error_code());
	}
	
	/**
	 * Verify that authenticated access to /wp/v2/users is allowed.
	 */
	public function test_get_users_authenticated() {
		wp_set_current_user(self::$editor);
		$request = new WP_REST_Request('GET', '/wp/v2/users');
		$response = $this->server->dispatch($request);
		$this->assertFalse(is_wp_error($response));
		$this->assertEquals(200, $response->get_status());
	}

	/**
	 * Verify that unauthenticated access to /wp/v2/posts is allowed.
	 */
	public function test_get_posts_unauthenticated() {
		$request = new WP_REST_Request('GET', '/wp/v2/posts');
		$response = $this->server->dispatch($request);
		$this->assertFalse(is_wp_error($response));
		$this->assertEquals(200, $response->get_status());
	}

	/**
	 * Verify that unauthenticated access to /wp/v2/posts?_embed is blocked.
	 */
	public function test_get_posts_embed_unauthenticated() {
		// Simulate ?_embed (this will be reset after each test)
		$_GET['_embed'] = '';
		$request = new WP_REST_Request('GET', '/wp/v2/posts');
		$response = $this->server->dispatch($request);
		$this->assertTrue(is_wp_error($response));
		$this->assertEquals('rest_forbidden', $response->get_error_code());
	}
}
