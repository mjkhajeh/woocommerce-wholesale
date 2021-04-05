<?php
namespace MJWCWS\Backend;

class WCMetabox {
	public static function get_instance() {
		static $instance = null;
		if( $instance === null ) {
			$instance = new self;
		}
		return $instance;
	}
	
	private function __construct() {
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'fields' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save' ), 10, 2 );
	}

	public function fields() {
		$post_id	= get_the_ID();
		$qty		= get_post_meta( $post_id, "mjwcws_qty", true );
		$price_type	= get_post_meta( $post_id, "mjwcws_price_type", true );
		$amount		= get_post_meta( $post_id, "mjwcws_amount", true );
		?>
		<div class="options_group">
			<?php
			woocommerce_wp_text_input( array(
				'id'	=> 'mjwcws_qty',
				'value'	=> $qty,
				'label'	=> __( "Wholesale quantity", 'mjwcws' ),
			) );
			woocommerce_wp_select( array(
				'id'		=> 'mjwcws_price_type',
				'value'		=> $price_type,
				'label'		=> __( "Wholesale price type", 'mjwcws' ),
				'options'	=> array(
					'price'		=> __( "Specified price", 'mjwcws' ),
					'percent'	=> __( "Percent", 'mjwcws' ),
				)
			) );
			woocommerce_wp_text_input( array(
				'id'	=> 'mjwcws_amount',
				'value'	=> $amount,
				'label'	=> __( "Amount", 'mjwcws' ),
			) );
			?>
		</div>
		<?php
	}

	public function save( $post_id, $post ) {
		$qty = '';
		if( isset( $_POST['mjwcws_qty'] ) && $_POST['mjwcws_qty'] ) {
			$qty = sanitize_text_field( $_POST['mjwcws_qty'] );
		}
		update_post_meta( $post_id, 'mjwcws_qty', $qty );

		$price_type = '';
		if( isset( $_POST['mjwcws_price_type'] ) && $_POST['mjwcws_price_type'] ) {
			$price_type = sanitize_text_field( $_POST['mjwcws_price_type'] );
		}
		update_post_meta( $post_id, 'mjwcws_price_type', $price_type );

		$amount = '';
		if( isset( $_POST['mjwcws_amount'] ) && $_POST['mjwcws_amount'] ) {
			$amount = sanitize_text_field( $_POST['mjwcws_amount'] );
		}
		update_post_meta( $post_id, 'mjwcws_amount', $amount );
	}
}
WCMetabox::get_instance();