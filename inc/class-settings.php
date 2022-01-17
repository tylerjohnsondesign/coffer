<?php
/**
 * Settings.
 * 
 * @since       1.0.0
 * @author      Tyler Johnson
 */
class cofferSettings {

    /**
     * Check save.
     */
    public function check_options() {

        // Check if options exist.
        if( empty( get_option( 'coffer_options' ) ) ) {

            // Create new.
            update_option( 'coffer_options', $this->set_options() );

        }

    }

    /**
     * Set options.
     */
    public function set_options() {

        // Set.
        $options = [
            'stripe-public'     => '',
            'stripe-secret'     => '',
            'donation-int'      => '',
            'box-position'      => '',
            'box-title'         => '',
            'box-text'          => '',
            'box-other'         => '',
            'primary-color'     => '',
            'secondary-color'   => '',
            'text-color'        => '',
            'box-color'         => '',
            'success-message'   => '',
        ];

        // Return.
        return $options;

    }

    /**
     * Get options.
     */
    public function get_options() {

        // Check if options exist.
        if( empty( get_option( 'coffer_options' ) ) ) {

            // Create new.
            update_option( 'coffer_options', $this->set_options() );

            // Send.
            return $this->set_options();

        }

        // Get options.
        return get_option( 'coffer_options' );

    }

    /**
     * Get single option.
     */
    public function get_option( $id ) {

        // Get.
        $options = $this->get_options();

        // Return.
        return ( isset( $options[$id] ) ) ? $options[$id] : false;

    }

    /**
     * Get fields.
     */
    public function get_fields() {

        // Set fields.
        $fields = [
            'stripe-public'     => [
                'type'              => 'text',
                'label'             => 'Stripe Public Key',
                'description'       => 'You can find your Stripe keys <a href="https://dashboard.stripe.com/apikeys" target="_blank">here</a>.',
                'options'           => [],
                'class'             => '',
            ],
            'stripe-secret'     => [
                'type'              => 'password',
                'label'             => 'Stripe Secret Key',
                'description'       => 'You can find your Stripe keys <a href="https://dashboard.stripe.com/apikeys" target="_blank">here</a>.',
                'options'           => [],
                'class'             => '',
            ],
            'donation-int'      => [
                'type'              => 'text',
                'label'             => 'Donation Amounts',
                'description'       => 'Comma separate list of valid donation amounts. Must use whole numbers only. Default/example: 5,10,15,25',
                'options'           => [],
                'class'             => '',
            ],
            'box-position'      => [
                'type'              => 'select',
                'label'             => 'Donation Box Position',
                'description'       => 'Location of the donation box. Can be placed anywhere via shortcode <pre>[coffer_form]</pre>',
                'options'           => [
                    'none'             => 'None',
                    'above-content'      => 'Above Content',
                    'below-content'    => 'Below Content',
                ],
                'class'             => '',
            ],
            'box-title'         => [
                'type'              => 'text',
                'label'             => 'Donation Box Title',
                'description'       => 'The title at the top of the donation box. Defaults to "Donate to ' . get_bloginfo( 'name' ) . '"',
                'options'           => [],
                'class'             => '',
            ],
            'box-text'          => [
                'type'              => 'textarea',
                'label'             => 'Donation Box Text',
                'description'       => 'The lead-in text after the box title. Defaults to: "If you enjoy our content and would like to support us, so that we can continue making great content, please consider giving a donation."',
                'options'           => [],
                'class'             => '',
            ],
            'success-message'   => [
                'type'              => 'textarea',
                'label'             => 'Success Message',
                'description'       => 'The text show to users after a successful donation. Default: "Success! Thank you for donating."',
                'options'           => [],
                'class'             => '',
            ],
            'box-other'         => [
                'type'              => 'select',
                'label'             => 'Donation Box Other',
                'description'       => 'Enable or disable the "Other" box for custom donation amounts.',
                'options'           => [
                    'on'               => 'On',
                    'off'              => 'Off',
                ],
                'class'             => '',
            ],
            'primary-color'     => [
                'type'              => 'text',
                'label'             => 'Primary Color',
                'description'       => 'The primary, highlight color for the donation box.',
                'options'           => [],
                'class'             => 'coffer-color',
            ],
            'secondary-color'   => [
                'type'              => 'text',
                'label'             => 'Secondary Color',
                'description'       => 'The secondary color for the donation box.',
                'options'           => [],
                'class'             => 'coffer-color',
            ],
            'text-color'   => [
                'type'              => 'text',
                'label'             => 'Text Color',
                'description'       => 'The text color for the donation box.',
                'options'           => [],
                'class'             => 'coffer-color',
            ],
            'box-color'   => [
                'type'              => 'text',
                'label'             => 'Box Color',
                'description'       => 'The background color for the donation box.',
                'options'           => [],
                'class'             => 'coffer-color',
            ],
        ];

        // Send.
        return $fields;

    }

    /** 
     * Save options.
     */
    public function save( $post ) {

        // Check.
        if( !empty( $post ) ) {

            // Get options.
            $options = $this->get_options();

            // Loop.
            foreach( $post as $key => $opt ) {

                // Set.
                $options[$key] = $opt;

            }

            // Save.
            update_option( 'coffer_options', $options );

            // Send.
            return $options;

        }

        // Nothing was saved.
        return false;

    }

    /**
     * Text field.
     * 
     * @includes        Passwords, color pickers.
     */
    public function text_field( $field, $id ) {

        // Set options.
        $type   = ( !empty( $field['type'] ) ) ? $field['type'] : 'text';
        $class  = ( !empty( $field['class'] ) ) ? ' class="' . $field['class'] . '"' : '';
        $value  = $this->get_option( $id );
        
        // Set output.
        $output = '';

        // Set output buffering.
        ob_start(); ?>

        <div class="coffer-field">
            <label for="<?php echo $id; ?>"><?php echo $field['label']; ?></label>
            <input name="<?php echo $id; ?>" id="<?php echo $id; ?>" type="<?php echo $type; ?>"<?php echo $class; ?> value="<?php echo $value; ?>" />
            <p class="description"><?php echo $field['description']; ?></p>
        </div><?php

        // Capture and end.
        $output = ob_get_clean();

        // Send.
        return $output;

    }

    /**
     * Select field.
     */
    public function select_field( $field, $id ) {

        // Set options.
        $type   = ( !empty( $field['type'] ) ) ? $field['type'] : 'text';
        $class  = ( !empty( $field['class'] ) ) ? ' class="' . $field['class'] . '"' : '';
        $value  = $this->get_option( $id );
        
        // Set output.
        $output = '';

        // Set output buffering.
        ob_start(); ?>

        <div class="coffer-field">
            <label for="<?php echo $id; ?>"><?php echo $field['label']; ?></label>
            <select name="<?php echo $id; ?>" id="<?php echo $id; ?>"><?php

                // Loop through options.
                foreach( $field['options'] as $option => $label ) {
                    
                    // Selected.
                    $selected = ( $value == $option ) ? ' selected' : ''; ?>

                    <option value="<?php echo $option; ?>"<?php echo $selected; ?>><?php echo $label; ?></option><?php

                } ?>

            </select>
            <p class="description"><?php echo $field['description']; ?></p>
        </div><?php

        // Capture and end.
        $output = ob_get_clean();

        // Send.
        return $output;

    }

    /**
     * Textarea field.
     */
    public function textarea_field( $field, $id ) {

        // Set options.
        $type   = ( !empty( $field['type'] ) ) ? $field['type'] : 'text';
        $class  = ( !empty( $field['class'] ) ) ? ' class="' . $field['class'] . '"' : '';
        $value  = $this->get_option( $id );
        
        // Set output.
        $output = '';

        // Set output buffering.
        ob_start(); ?>

        <div class="coffer-field">
            <label for="<?php echo $id; ?>"><?php echo $field['label']; ?></label>
            <textarea name="<?php echo $id; ?>" id="<?php echo $id; ?>"><?php echo $value; ?></textarea>
            <p class="description"><?php echo $field['description']; ?></p>
        </div><?php

        // Capture and end.
        $output = ob_get_clean();

        // Send.
        return $output;

    }

}