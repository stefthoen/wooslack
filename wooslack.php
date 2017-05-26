<?php

/**
 * Plugin Name: Wooslack
 * Description: Connect WooCommerce with Slack.
 * Author:      Stef Thoen
 * Author URI:  http://stef.co
 * Version:     1.0.0
 * Text Domain: wooslack
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages/
 */

Define("SLACK_URL", "https://hooks.slack.com/services/");
Define("SLACK_WEBHOOK_ID", "");

add_action( 'woocommerce_order_status_completed', 'wooslack_order_completed', 10, 1 );
function wooslack_order_completed($order_id)
{
    $order = wc_get_order($order_id);
    $p_order = $order->get_data();
    $message = sprintf("New order by %s %s for %s %s!",
        $p_order['billing']['first_name'],
        $p_order['billing']['last_name'],
        $p_order['currency'],
        $p_order['total']);

    wp_remote_post(
        esc_url_raw(SLACK_URL . SLACK_WEBHOOK_ID),
        [
            'method' => 'POST',
            'timeout' => 45,
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(["text" => $message])
        ]
    );
}

