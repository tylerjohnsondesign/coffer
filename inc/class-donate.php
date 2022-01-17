<?php
/**
 * Donate.
 * 
 * @since       1.0.0
 * @author      Tyler Johnson
 */
class cofferDonate {

    /**
     * Variables.
     */
    private $key = '';

    /**
     * Construct.
     */
    public function __construct() {

        // Set key.
        $this->key = $this->get_secret();

        // Actions.
        add_action( 'wp_ajax_create_payment_intent', [ $this, 'create_payment_intent' ] );
        add_action( 'wp_ajax_nopriv_create_payment_intent', [ $this, 'create_payment_intent' ] );
        add_action( 'wp_ajax_update_payment_intent', [ $this, 'update_payment_intent' ] );
        add_action( 'wp_ajax_nopriv_update_payment_intent', [ $this, 'update_payment_intent' ] );

    }

    /**
     * Get Stripe secret.
     */
    private function get_secret() {

        // Settings.
        $settings = new cofferSettings;

        // Get key.
        return $settings->get_option( 'stripe-secret' );

    }

    /**
     * Create payment intent.
     */
    public function create_payment_intent() {

        // Stripe SDK.
        \Stripe\Stripe::setApiKey( $this->key );

        // Amount.
        if( !empty( $_POST['amount'] ) ) {

            // Set.
            $amount = $_POST['amount'] . '00';

            // Make sure we have a number.
            filter_var( $amount, FILTER_VALIDATE_INT, [ 'options' => [ 'min_range' => 1 ] ] );

            // Create intent.
            $intent = \Stripe\PaymentIntent::create( [
                'amount'    => $amount,
                'currency'  => 'usd',
                'metadata'  => [ 'integration_check' => 'accept_a_payment' ],
            ] );

            // Get client secret.
            if( !empty( $intent->client_secret ) ) {

                // Send.
                echo json_encode( [ 'intent_id' => $intent->id, 'client_secret' => $intent->client_secret ] );

            } else {

                // Send error.
                echo json_encode( [ 'error' => 'Payment intent failed.' ] );

            }

        } else {

            // Send error.
            echo json_encode( [ 'error' => 'Missing payment amount.' ] );

        }

        // End.
        wp_die();

    }

    /**
     * Update payment intent.
     */
    public function update_payment_intent() {

        // Stripe SDK.
        \Stripe\Stripe::setApiKey( $this->key );

        // Amount.
        if( !empty( $_POST['amount'] ) && !empty( $_POST['id'] ) ) {

            // Set.
            $amount = $_POST['amount'] . '00';

            // Make sure we have a number.
            filter_var( $amount, FILTER_VALIDATE_INT, [ 'options' => [ 'min_range' => 1 ] ] );

            // Update intent.
            $intent = \Stripe\PaymentIntent::update( $_POST['id'], [
                'amount'    => $amount,
            ] );

            echo 'success';

        } else {

            // Send error.
            echo json_encode( [ 'error' => 'Missing payment amount.' ] );

        }

        // End.
        wp_die();

    }

}
new cofferDonate;