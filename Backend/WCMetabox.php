<?php
namespace MJWCWS\Backend;

class WCMetabox {
	PRIVATE $PREFIX = 'mjwcws_';
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
		$post_id			= get_the_ID();
		$qty				= get_post_meta( $post_id, "{$this->PREFIX}qty", true );
		$price_type			= get_post_meta( $post_id, "{$this->PREFIX}price_type", true );
		$price				= get_post_meta( $post_id, "{$this->PREFIX}price", true );
		$sale_price_type	= get_post_meta( $post_id, "{$this->PREFIX}sale_price_type", true );
		$sale_price			= get_post_meta( $post_id, "{$this->PREFIX}sale_price", true );
		?>
		<div class="options_group">
			<h3><?php _e( "Wholesale", 'mjwcws' ) ?></h3>
			<?php
			woocommerce_wp_text_input( array(
				'id'	=> "{$this->PREFIX}qty",
				'value'	=> $qty,
				'label'	=> __( "Wholesale minimum quantity", 'mjwcws' ),
			) );
			woocommerce_wp_select( array(
				'id'		=> "{$this->PREFIX}price_type",
				'value'		=> $price_type,
				'label'		=> __( "Wholesale price type", 'mjwcws' ),
				'options'	=> array(
					'price'		=> __( "Specified price", 'mjwcws' ),
					'percent'	=> __( "Percent", 'mjwcws' ),
				)
			) );
			woocommerce_wp_text_input( array(
				'id'	=> "{$this->PREFIX}price",
				'value'	=> $price,
				'label'	=> __( "Price", 'mjwcws' ),
			) );

			woocommerce_wp_select( array(
				'id'		=> "{$this->PREFIX}sale_price_type",
				'value'		=> $sale_price_type,
				'label'		=> __( "Wholesale sale price type", 'mjwcws' ),
				'options'	=> array(
					'price'		=> __( "Specified price", 'mjwcws' ),
					'percent'	=> __( "Percent", 'mjwcws' ),
				)
			) );
			woocommerce_wp_text_input( array(
				'id'	=> "{$this->PREFIX}sale_price",
				'value'	=> $sale_price,
				'label'	=> __( "Sale price", 'mjwcws' ),
			) );
			?>
		</div>
		<?php
	}

	public function save( $post_id, $post ) {
		$qty = '';
		if( !empty( $_POST["{$this->PREFIX}qty"] ) ) {
			$qty = sanitize_text_field( $_POST["{$this->PREFIX}qty"] );
		}
		update_post_meta( $post_id, "{$this->PREFIX}qty", $qty );

		$price_type = '';
		if( !empty( $_POST["{$this->PREFIX}price_type"] ) ) {
			$price_type = sanitize_text_field( $_POST["{$this->PREFIX}price_type"] );
		}
		update_post_meta( $post_id, "{$this->PREFIX}price_type", $price_type );

		$price = '';
		if( !empty( $_POST["{$this->PREFIX}price"] ) ) {
			$price = sanitize_text_field( $_POST["{$this->PREFIX}price"] );
		}
		update_post_meta( $post_id, "{$this->PREFIX}price", $price );

		$sale_price_type = '';
		if( !empty( $_POST["{$this->PREFIX}sale_price_type"] ) ) {
			$sale_price_type = sanitize_text_field( $_POST["{$this->PREFIX}sale_price_type"] );
		}
		update_post_meta( $post_id, "{$this->PREFIX}sale_price_type", $sale_price_type );

		$sale_price = '';
		if( !empty( $_POST["{$this->PREFIX}sale_price"] ) ) {
			$sale_price = sanitize_text_field( $_POST["{$this->PREFIX}sale_price"] );
		}
		update_post_meta( $post_id, "{$this->PREFIX}sale_price", $sale_price );
	}
}
WCMetabox::get_instance();