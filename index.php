<?php
/*
Plugin Name: WooCommerce Ooredoo SMS Plugin
Description: Sends an SMS when a WooCommerce order status is set to "processing" using the Ooredoo SMS Gateway API.
Version: 2.0
Author: Mifzaal Abdul Baari
Author URI: https://islandboy.mv
*/


function format_phone_number( $phone ) {
    // Remove any white space from the phone number
    $phone = preg_replace( '/\s+/', '', $phone );

    // If the phone number starts with "960" followed by 7 digits, leave it as is
    if ( preg_match( '/^960\d{7}$/', $phone ) ) {
        return $phone;
    }

    // If the phone number has a "+" at the beginning, remove it
    if ( preg_match( '/^\+\d+$/', $phone ) ) {
        return ltrim( $phone, '+' );
    }

    // If the phone number has 7 digits, add "960" to the beginning
    if ( preg_match( '/^\d{7}$/', $phone ) ) {
        return '960' . $phone;
    }

    // If the phone number doesn't match any of the above patterns, return it as is
    return $phone;
}


function send_sms_notification( $order_id, $old_status, $new_status ) {
    // Check if the new order status is "processing"
    if ( 'processing' === $new_status ) {
        // Replace "unknown" with your actual Bearer token and username and access key
        $bearer_token = get_option( 'woocommerce_ooredoo_sms_bearer_token' );
        $username = get_option( 'woocommerce_ooredoo_sms_username' );
        $access_key = get_option( 'woocommerce_ooredoo_sms_access_key' );

        // Get the phone number and first name for the order
        $order = wc_get_order( $order_id );
        $phone = $order->get_billing_phone();
        $first_name = $order->get_billing_first_name();


        // Format the phone number
        $phone = format_phone_number( $phone );

        // Capitalize the first name
        $first_name = ucwords( $first_name );

        // Set the message and batch variables
        $message = "Dear {$first_name},\nYour order #{$order_id} is ready for pickup at The Scout Association of Maldives.";
        $batch = $phone;

        // Set the cURL options
        $options = array(
            'headers' => array(
                'Authorization' => "Bearer $bearer_token"
            ),
            'body' => array(
                'username' => $username,
                'access_key' => $access_key,
                'message' => $message,
                'batch' => $batch
            )
        );

        // Send the HTTP POST request
        $response = wp_remote_post( 'https://o-papi1-lb01.ooredoo.mv/bulk_sms/v2', $options );

        // Check for successful request
        if ( ! is_wp_error( $response ) ) {
            // Request was successful, do something here (optional)
        } else {
            // There was an error, do something here (optional)
        }
    }
}
add_action( 'woocommerce_order_status_changed', 'send_sms_notification', 10, 3 );


// Added Admin Page Capabilities

// Adds a top-level menu item to the WordPress admin dashboard
function woocommerce_ooredoo_sms_menu() {
    add_menu_page( 'WooCommerceOoredooSMS', 'WooCommerceOoredooSMS', 'manage_options', 'woocommerce-ooredoo-sms', 'woocommerce_ooredoo_sms_options_page', 'dashicons-admin-generic', 100 );
}
add_action( 'admin_menu', 'woocommerce_ooredoo_sms_menu' );

// Displays the plugin options page
function woocommerce_ooredoo_sms_options_page() {
    ?>
    <div class="wrap">
        <h1>WooCommerce Ooredoo SMS Plugin</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'woocommerce_ooredoo_sms_options' );
            do_settings_sections( 'woocommerce_ooredoo_sms_options' );
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Bearer Token</th>
                    <td><input type="text" name="woocommerce_ooredoo_sms_bearer_token" value="<?php echo esc_attr( get_option( 'woocommerce_ooredoo_sms_bearer_token' ) ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Username</th>
                    <td><input type="text" name="woocommerce_ooredoo_sms_username" value="<?php echo esc_attr( get_option( 'woocommerce_ooredoo_sms_username' ) ); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Access Key</th>
                    <td><input type="text" name="woocommerce_ooredoo_sms_access_key" value="<?php echo esc_attr( get_option( 'woocommerce_ooredoo_sms_access_key' ) ); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Tells WordPress which settings your plugin uses and where to store them in the database.
function woocommerce_ooredoo_sms_register_settings() {
    register_setting( 'woocommerce_ooredoo_sms_options', 'woocommerce_ooredoo_sms_bearer_token', 'sanitize_text_field' );
    register_setting( 'woocommerce_ooredoo_sms_options', 'woocommerce_ooredoo_sms_username', 'sanitize_text_field' );
    register_setting( 'woocommerce_ooredoo_sms_options', 'woocommerce_ooredoo_sms_access_key', 'sanitize_text_field' );
}
add_action( 'admin_init', 'woocommerce_ooredoo_sms_register_settings' );


