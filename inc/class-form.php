<?php
/**
 * Donate.
 * 
 * @since       1.0.0
 * @author      Tyler Johnson
 */
class cofferForm {

    /**
     * Construct.
     */
    public function __construct() {

        // Enqueue.
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );

        // Check method.
        if( $this->get_method() == 'above-content' || $this->get_method() == 'below-content' ) {

            // Append to content.
            add_filter( 'the_content', [ $this, 'content' ], 9999, 1 );

        }

        // Shortcode.
        add_shortcode( 'coffer_form', [ $this, 'shortcode' ] );

    }

    /**
     * Enqueue.
     */
    public function enqueue() {

        // CSS.
        wp_enqueue_style( 'coffer-css', COFFER_URI . 'assets/css/coffer.css', [], COFFER_VERSION, 'all' );

        // JS.
        wp_enqueue_script( 'stripe-js', 'https://js.stripe.com/v3/', [ 'jquery' ], COFFER_VERSION, false );
        wp_enqueue_script( 'coffer-js', COFFER_URI . 'assets/js/coffer.js', [ 'jquery', 'stripe-js' ], COFFER_VERSION, true );

        // Get settings.
        $settings = new cofferSettings;

        // Localise.
        wp_localize_script( 'coffer-js', 'coffer', [ 'ajax_url' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'coffer_nonce' ), 'stripe' => $settings->get_option( 'stripe-public' ) ] );

    }

    /**
     * Get method.
     */
    public function get_method() {

        // Settings.
        $settings = new cofferSettings;

        // Send method.
        return $settings->get_option( 'box-position' );

    }

    /**
     * Get the form.
     */
    public function form() {

        // Set output.
        $output = '';

        // Start output buffering.
        ob_start();

        // Get settings.
        $settings = new cofferSettings;

        // Set.
        $title   = ( !empty( $settings->get_option( 'box-title' ) ) ) ? $settings->get_option( 'box-title' ) : 'Donate to ' . get_bloginfo( 'name' );
        $text    = ( !empty( $settings->get_option( 'box-text' ) ) ) ? wpautop( $settings->get_option( 'box-text' ) ) : '<p>If you enjoy our content and would like to support us, so that we can continue making great content, please consider giving a donation.</p>';
        $success = ( !empty( $settings->get_option( 'success-message' ) ) ) ? $settings->get_option( 'success-message' ) : '<strong>Success!</strong> Thank you for your donation.';
        // Form HTML. ?>
        <div class="coffer-box">
            <div class="coffer-inner">
                <div id="coffer-panel-1" class="coffer-panel">
                    <div class="coffer-head">
                        <h2><?php echo $title; ?></h2>
                    </div>
                    <div class="coffer-body">
                        <?php echo $text; ?>
                    </div>
                    <div class="coffer-footer">
                        <div class="coffer-options"><?php

                            // Set default options.
                            $options = [ 5, 10, 15, 25 ];

                            // Check for options.
                            if( !empty( $settings->get_option( 'donation-int' ) ) ) {

                                // Get.
                                $set_options = $settings->get_option( 'donation-int' );

                                // Create.
                                $options = explode( ',', trim( $set_options ) );

                            }

                            // Loop through options.
                            foreach( $options as $option ) { ?>

                                <div id="coffer-option-<?php echo $option; ?>" class="coffer-option" data-amount="<?php echo $option; ?>"><span>$<?php echo $option; ?></span></div><?php

                            }
                            
                            // Check for other.
                            if( !empty( $settings->get_option( 'box-other' ) ) && $settings->get_option( 'box-other' ) == 'on' ) { ?>

                                <div class="coffer-option">
                                    <span id="coffer-open" data-id="coffer-option-other">Other</span>
                                </div><?php

                            } ?>

                        </div><?php

                        // Check for other.
                        if( !empty( $settings->get_option( 'box-other' ) ) && $settings->get_option( 'box-other' ) == 'on' ) { ?>

                            <div id="coffer-option-other" class="coffer-option-other">
                                <span class="coffer-option-other-label">$</span>
                                <input type="number" id="coffer-option-custom" value="" />
                                <button type="button" class="coffer-btn">Pay</button>
                            </div><?php

                        } ?>

                    </div>
                </div>
                <div id="coffer-panel-2" class="coffer-panel">
                    <form id="payment-form">
                        <div id="card-element">
                            <!-- Elements will create input elements here -->
                        </div>

                        <!-- We'll put the error messages in this element -->
                        <div id="card-errors" role="alert"></div>

                        <button id="submit">Donate</button>
                    </form>
                    <div class="coffer-loading">
                        <?php include COFFER_PATH . 'assets/images/loading.svg'; ?>
                    </div>
                    <div class="coffer-success">
                        <div class="coffer-success-inner">
                            <?php echo $success; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style><?php 

            // Set colors.
            $primary    = ( !empty( $settings->get_option( 'primary-color' ) ) ) ? $settings->get_option( 'primary-color' ) : '#0c5aa6';
            $secondary  = ( !empty( $settings->get_option( 'secondary-color' ) ) ) ? $settings->get_option( 'secondary-color' ) : '#03294d'; ?>

            .coffer-option {
                background: <?php echo $secondary; ?>;
            }

            .coffer-option.active, .coffer-panel button#submit, div#coffer-option-other button.coffer-btn {
                background: <?php echo $primary; ?>;
            }

            div#coffer-option-other button.coffer-btn {
                border: 1px solid <?php echo $primary; ?>;
            }

            .coffer-success-inner strong {
                color: <?php echo $primary; ?>;
            }

        </style><?php

        // Capture.
        $output = ob_get_clean();

        // Return.
        return $output;

    }

    /**
     * Shortcode.
     */
    public function shortcode() {

        // Send form.
        return $this->form();

    }

    /**
     * Content.
     */
    public function content( $content ) {

        // Check for single.
        if( is_single() ) {

            // Check location.
            if( $this->get_method() == 'above-content' ) {

                // Prepend.
                $content = $this->form() . $content;
                
            } else {

                // Append.
                $content .= $this->form();

            }

        }

        // Return.
        return $content;

    }

}
new cofferForm;