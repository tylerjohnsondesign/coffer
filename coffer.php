<?php
/*
Plugin Name: Coffer
Plugin URI: https://tylerjohnsondesign.com
Description: A simple, Stripe donation plugin.
Version: 1.0.0
Author: Tyler Johnson
Author URI: https://tylerjohnsondesign.com
Copyright: Tyler Johnson
Text Domain: coffer
Copyright © 2022 Tyler Johnson. All Rights Reserved.
*/

/**
 * Disallow Direct Access to Plugin File
 */
if( !defined( 'WPINC' ) ) { die; }

/**
 * Constants
 */
define( 'COFFER_VERSION', '1.0.0' );
define( 'COFFER_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'COFFER_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

/**
 * Plugin.
 */
class cofferPlugin {

    /**
     * Construct.
     */
    public function __construct() {

        // Run.
        $this->load();
        $this->update();

    }

    /**
     * Load.
     */
    public function load() {

        // Classes.
        require_once( COFFER_PATH . 'stripe/init.php' );
        require_once( COFFER_PATH . 'inc/class-settings.php' );
        require_once( COFFER_PATH . 'inc/class-donate.php' );
        require_once( COFFER_PATH . 'inc/class-form.php' );
        require_once( COFFER_PATH . 'inc/class-admin.php' );

    }

    /**
     * Update.
     */
    public function update() {

        // Load updates.
        require COFFER_PATH . 'update/plugin-update-checker.php';

        // Set update.
        $update = Puc_v4_Factory::buildUpdateChecker(
            'https://github.com/tylerjohnsondesign/coffer',
            __FILE__,
            'coffer'
        );

        // Stable branch release.
        $update->setBranch('main');

        // Private repo access key.
        $update->setAuthentication('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');

    }

}
new cofferPlugin;