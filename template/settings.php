<?php
// Settings.
$settings = new cofferSettings;

// Run settings operations.
$settings->check_options();
$settings->save( $_POST );
$fields = $settings->get_fields(); ?>

<div class="coffer-wrap">
    <div class="coffer-inner">
        <div class="coffer-head">
            <h2>Coffer</h2>
        </div>
        <div class="coffer-body">
            <form method="post"><?php

                // Loop through options.
                foreach( $fields as $id => $field ) {

                    // Check.
                    if( $field['type'] == 'text' || $field['type'] == 'password' ) {

                        // Get template.
                        echo $settings->text_field( $field, $id );

                    } elseif( $field['type'] == 'textarea' ) {

                        // Get template.
                        echo $settings->textarea_field( $field, $id );

                    } elseif( $field['type'] == 'select' ) {

                        // Get template.
                        echo $settings->select_field( $field, $id );

                    }

                } ?>
            
                <input id="coffer-save" type="submit" value="Save" />
            </form>
        </div>
    </div>
</div>