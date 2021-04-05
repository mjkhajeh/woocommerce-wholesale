<?php
/*
Plugin Name: Woocommerce Wholesale
Plugin URI: http://mjkhajeh.com
Description: Changes the price of the product in the shopping cart according to its quantity
Version: 1.0.0.0
Author: Mohammad Jafar Khajeh
Author URI: http://mjkhajeh.com
Text Domain: mjwcws
Domain Path: /languages
*/
namespace MJWCWS;

if( !defined( 'ABSPATH' ) ) exit;

class Init {
	public static function get_instance() {
		static $instance = null;
		if( $instance === null ) {
			$instance = new self;
		}
		return $instance;
	}
	
	private function __construct() {
		// Bootstrap APIs
		$this->i18n();
		$this->constants();
		$this->includes();

		add_action( 'woocommerce_before_calculate_totals', array( $this, 'calculate_totals' ) );
	}

	private function i18n() {
		load_plugin_textdomain( 'mjwcws', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	private function constants() {
		if( ! defined( 'MJWCWS_DIR' ) )
			define( 'MJWCWS_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		if( ! defined( 'MJWCWS_URI' ) )
			define( 'MJWCWS_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
	}

	private function includes() {
		include_once( MJWCWS_DIR . "Backend/WCMetabox.php" );
	}

	public function calculate_totals( $cart_object ) {
		foreach( $cart_object->get_cart() as $hash => $value ) {
			$post_id		= $value['product_id'];
			$user_quantity	= $value['quantity'];
			$qty			= get_post_meta( $post_id, "mjwcws_qty", true );
			if( $qty && $user_quantity < $qty ) {
				$price_type		= get_post_meta( $post_id, "mjwcws_price_type", true );
				$amount			= get_post_meta( $post_id, "mjwcws_amount", true );
				$product_price	= $value['data']->get_regular_price();
				if( $value['data']->get_sale_price() ) {
					$product_price = $value['data']->get_sale_price();
				}
				$new_price	= $product_price;
				if( $price_type == 'percent' ) {
					$new_price += ($amount/100)*$product_price;
				} else {
					$new_price += $amount;
				}
				$value['data']->set_price( $new_price );
			}
		}
	}
}
Init::get_instance();