<?php
/**
 * Admin.
 * 
 * @since       1.0.0
 * @author      Tyler Johnson
 */
class cofferAdmin {

    /**
     * Construct.
     */
    public function __construct() {

        // Actions.
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue' ] );
        add_action( 'admin_menu', [ $this, 'plugin_menu' ] );

    }

    /**
     * Enqueue.
     */
    public function enqueue() {

        // CSS.
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'coffer-admin-css', COFFER_URI . 'assets/css/coffer-admin.css', [], COFFER_VERSION, 'all' );

        // JS.
        wp_enqueue_script( 'coffer-admin-js', COFFER_URI . 'assets/js/coffer-admin.js', [ 'jquery', 'wp-color-picker' ], COFFER_VERSION, true );

    }

    /** 
	 * Plugin menu.
	 */
	public function plugin_menu() {
		
		// Add settings.
		add_submenu_page(
			'options-general.php',
			__( 'Coffer', 'coffer' ),
			__( 'Coffer', 'coffer' ),
			'manage_options',
			'coffer-settings',
			[ $this, 'coffer_settings' ]
		);

	}

    /**
     * Settings.
     */
    public function coffer_settings() {

        // Template.
        include COFFER_PATH . 'template/settings.php';

    }

}
new cofferAdmin;