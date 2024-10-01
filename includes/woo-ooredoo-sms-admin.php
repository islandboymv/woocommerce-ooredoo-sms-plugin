<?php

class WooOoredooSMSAPI {

    public function send_sms( $phone, $message ) {
        $bearer_token = get_option( 'woocommerce_ooredoo_sms_bearer_token' );
        $username = get_option( 'woocommerce_ooredoo_sms_username' );
        $access_key = get_option( 'woocommerce_ooredoo_sms_access_key' );
        $hashed_access_key = base64_encode( $access_key );

        $options = array(
            'headers' => array(
                'Authorization' => "Bearer $bearer_token"
            ),
            'body' => array(
                'username' => $username,
                'access_key' => $hashed_access_key,
                'message' => $message,
                'batch' => $phone
            )
        );

        $response = wp_remote_post( 'https://o-papi1-lb01.ooredoo.mv/bulk_sms/v2', $options );

        if ( is_wp_error( $response ) ) {
            error_log( 'Ooredoo SMS API error: ' . $response->get_error_message() );
        }
    }
}
