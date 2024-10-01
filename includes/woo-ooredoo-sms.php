<?php

class WooOoredooSMS {

    public function initialize() {
        // Hook into WooCommerce order status changes
        add_action( 'woocommerce_order_status_changed', array( $this, 'send_sms_notification' ), 10, 3 );
    }

    public function send_sms_notification( $order_id, $old_status, $new_status ) {
        $statuses_to_notify = array( 'on-hold', 'completed', 'processing' );

        if ( in_array( $new_status, $statuses_to_notify ) ) {
            $order = wc_get_order( $order_id );
            $phone = $this->format_phone_number( $order->get_billing_phone() );
            $first_name = ucwords( $order->get_billing_first_name() );

            $messages = array(
                'on-hold' => "Dear {$first_name},\nWe have received your order #{$order_id}.",
                'processing' => "Dear {$first_name},\nYour order #{$order_id} is ready for pickup at The Scout Association of Maldives.",
                'completed' => "Dear {$first_name},\nYour order #{$order_id} has been collected from The Scout Association of Maldives.",
            );

            $message = isset( $messages[ $new_status ] ) ? $messages[ $new_status ] : '';

            if ( ! empty( $message ) ) {
                $api = new WooOoredooSMSAPI();
                $api->send_sms( $phone, $message );
            }
        }
    }

    public function format_phone_number( $phone ) {
        $phone = preg_replace( '/\s+/', '', $phone );
        if ( preg_match( '/^960\d{7}$/', $phone ) ) {
            return $phone;
        }
        if ( preg_match( '/^\+\d+$/', $phone ) ) {
            return ltrim( $phone, '+' );
        }
        if ( preg_match( '/^\d{7}$/', $phone ) ) {
            return '960' . $phone;
        }
        return $phone;
    }
}
