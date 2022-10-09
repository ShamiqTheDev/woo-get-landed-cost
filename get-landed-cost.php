<?php
/**
 * Plugin Name: Get Landing Cost
 * Plugin URI: https://www.linkedin.com/in/muhammad-shamiq-hussain-developer/
 * Description: This plugin adds “landed cost” details under the Total on checkout page
 * Version: 1.0
 * Author: Muhammad Shamiq Hussain
 * Author URI: https://www.linkedin.com/in/muhammad-shamiq-hussain-developer/
 */

require_once 'includes/functions.php';


if (!function_exists('glc_plugin_scripts')) {
	/**
	 * Loads all plugin scripts
	 * @return void
	 */
	function glc_plugin_scripts() {
		/* Loading plugin and dependency style sheets */
		wp_enqueue_style( 'landing_cost_css', plugin_dir_url( __FILE__ ).'assets/css/glc_main.css');

		/* Loading plugin and dependency js */
		wp_enqueue_script( 'landing_cost_js', plugin_dir_url( __FILE__ ).'assets/js/glc_main.js', array('jquery'), '1.0.0' , true );
		wp_localize_script( 'landing_cost_js', 'ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ), '1.0.0' , true );
	}
	add_action('wp_enqueue_scripts', 'glc_plugin_scripts');
}


if (!function_exists('glc_row_html')) {
	/**
	 * Sets Landing Cost HTML under the Total on checkout page
	 * @return void
	 */
	function glc_row_html() {
		$html = '<tr class="order-landing-cost">'.
			'<th>Landing Cost</th>'.
			'<td>'.
				'<strong class="landing-cost-amount">'.wc_price(0).'</strong> '.
			'</td>'.
		'</tr>'; //end table row

	    echo $html;
	}
	add_action('woocommerce_review_order_after_order_total', 'glc_row_html');
}


if (!function_exists('glc_get_landing_cost')) {
	/**
	 * AJAX Get Landing Cost
	 * @return void
	 */
	function glc_get_landing_cost() {
		global $woocommerce;
	    $cart = $woocommerce->cart;

	    $order_total = $woocommerce->cart->get_cart_total();
	    $order_country = $woocommerce->customer->get_shipping_country();

	    $url = '';
	    $client_secret = '';
		$data_arr = array(
			'client_id' 		=> $client_secret,
			'order_total' 		=> $order_total,
			'order_country' 	=> $order_country,
		);
		$response_obj = glc_post($url,$data_arr);

		
	    if (isset($response_obj->landed_cost)) {
		    $landing_cost = $response_obj->landed_cost??0;	
		    $return_response = array(
		    	'code' => 1 ,
		    	'entity' => array(
		    		'landed_cost' => wc_price($landing_cost),
		    	),
		    );
	    } else {
	    	$return_response = array(
		    	'code' => 0 ,
		    	'message' => 'Unable to fetch landed cost for this order',
		    );
	    }
	    echo json_encode($return_response);
	    wp_die();
	}
	add_action( 'wp_ajax_glc_get_landing_cost', 'glc_get_landing_cost');
	add_action( 'wp_ajax_nopriv_glc_get_landing_cost', 'glc_get_landing_cost');
}