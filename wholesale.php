<?php
/*
Plugin Name: Wholesale
Plugin URI: http://wordpress.org/plugins/wholesale
Description: Changes the price of the product in the shopping cart according to its quantity
Version: 1.0.0.0
Author: MohammadJafar Khajeh
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

		add_action( 'woocommerce_before_calculate_totals', array( $this, 'calculate_cart' ) );
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

	public function calculate_cart( $cart_object ) {
		foreach( $cart_object->get_cart() as $hash => $value ) {
			$post_id		= $value['product_id'];
			$user_quantity	= $value['quantity'];
			$qty			= get_post_meta( $post_id, "mjwcws_qty", true );
			if( !empty( $qty ) && $user_quantity >= $qty ) {
				$regular_price	= $value['data']->get_regular_price();
				$sale_price 	= $value['data']->get_sale_price();
				if( empty( $sale_price ) ) { // Regular price
					$new_price	= $regular_price;
					$price_type	= get_post_meta( $post_id, "mjwcws_price_type", true );
					$price		= get_post_meta( $post_id, "mjwcws_price", true );
				} else {
					$new_price	= $sale_price;
					$price_type	= get_post_meta( $post_id, "mjwcws_sale_price_type", true );
					$price		= get_post_meta( $post_id, "mjwcws_sale_price", true );
				}
				
				if( $price_type == 'price' ) {
					$new_price = $price;	
				} else if( $price_type == 'percent' ) {
					$new_price -= ($price/100)*$new_price;
				}
				$value['data']->set_price( $new_price );
			}
		}
	}
}
Init::get_instance();