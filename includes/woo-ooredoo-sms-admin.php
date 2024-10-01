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
    }
}

// Initialize the admin class
if ( is_admin() ) {
    new WooOoredooSMSAdmin();
}
