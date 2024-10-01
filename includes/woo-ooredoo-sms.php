<?php

class WooOoredooSMSAdmin {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    public function add_settings_page() {
        add_menu_page( 'WooCommerce Ooredoo SMS', 'Ooredoo SMS Settings', 'manage_options', 'woocommerce-ooredoo-sms', array( $this, 'settings_page' ), 'dashicons-admin-generic', 100 );
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1>WooCommerce Ooredoo SMS Plugin</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'woocommerce_ooredoo_sms_options' );
                do_settings_sections( 'woocommerce_ooredoo_sms_options' );
                ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">Bearer Token</th>
                        <td><input type="text" name="woocommerce_ooredoo_sms_bearer_token" value="<?php echo esc_attr( get_option( 'woocommerce_ooredoo_sms_bearer_token' ) ); ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Username</th>
                        <td><input type="text" name="woocommerce_ooredoo_sms_username" value="<?php echo esc_attr( get_option( 'woocommerce_ooredoo_sms_username' ) ); ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">Access Key</th>
                        <td><input type="text" name="woocommerce_ooredoo_sms_access_key" value="<?php echo esc_attr( get_option( 'woocommerce_ooredoo_sms_access_key' ) ); ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">On-hold Message</th>
                        <td><textarea name="woocommerce_ooredoo_sms_on_hold_message"><?php echo esc_textarea( get_option( 'woocommerce_ooredoo_sms_on_hold_message', 'Dear {first_name},\nWe have received your order #{order_id}.' ) ); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row">Processing Message</th>
                        <td><textarea name="woocommerce_ooredoo_sms_processing_message"><?php echo esc_textarea( get_option( 'woocommerce_ooredoo_sms_processing_message', 'Dear {first_name},\nYour order #{order_id} is ready for pickup at The Scout Association of Maldives.' ) ); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row">Completed Message</th>
                        <td><textarea name="woocommerce_ooredoo_sms_completed_message"><?php echo esc_textarea( get_option( 'woocommerce_ooredoo_sms_completed_message', 'Dear {first_name},\nYour order #{order_id} has been collected from The Scout Association of Maldives.' ) ); ?></textarea></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function register_settings() {
        register_setting( 'woocommerce_ooredoo_sms_options', 'woocommerce_ooredoo_sms_bearer_token', 'sanitize_text_field' );
        register_setting( 'woocommerce_ooredoo_sms_options', 'woocommerce_ooredoo_sms_username', 'sanitize_text_field' );
        register_setting( 'woocommerce_ooredoo_sms_options', 'woocommerce_ooredoo_sms_access_key', 'sanitize_text_field' );

        // Register settings for the message templates
        register_setting( 'woocommerce_ooredoo_sms_options', 'woocommerce_ooredoo_sms_on_hold_message', 'sanitize_textarea_field' );
        register_setting( 'woocommerce_ooredoo_sms_options', 'woocommerce_ooredoo_sms_processing_message', 'sanitize_textarea_field' );
        register_setting( 'woocommerce_ooredoo_sms_options', 'woocommerce_ooredoo_sms_completed_message', 'sanitize_textarea_field' );
    }
}

// Initialize the admin class
if ( is_admin() ) {
    new WooOoredooSMSAdmin();
}
