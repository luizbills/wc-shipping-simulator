<?php

namespace Shipping_Simulator;

use Shipping_Simulator\Helpers as h;
use Shipping_Simulator\Ajax;

final class Shortcode {
	public function __start () {
		add_shortcode( self::get_tag(), [ $this, 'render_shortcode' ] );
	}

	public static function get_tag () {
		return 'wc_shipping_simulator';
	}

	public function render_shortcode ( $atts ) {
		$atts = shortcode_atts( [
			'product' => 0,
		], $atts, self::get_tag() );

		$this->enqueue_scripts();

		$product = wc_get_product( $atts['product'] );

		return h::get_template( 'shipping-simulator-form', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'ajax_action' => Ajax::get_ajax_action(),
			'nonce' => Ajax::get_nonce_field(),
			'product_type' => $product->get_type(),
			'product_id' => $product->get_id(),

			// customizable template variables
			'input_mask' => apply_filters(
				'wc_shipping_simulator_form_input_mask',
				'' // no input mask by default
			),
			'input_placeholder' => apply_filters(
				'wc_shipping_simulator_form_input_placeholder',
				__( 'Type your postcode', 'wc-shipping-simulator' )
			),
			'input_type' => apply_filters(
				'wc_shipping_simulator_form_input_type',
				'tel'
			),
			'input_value' => apply_filters(
				'wc_shipping_simulator_form_input_value',
				$this->get_customer_postcode()
			),
			'submit_label' => apply_filters(
				'wc_shipping_simulator_form_submit_label',
				__( 'Apply', 'wc-shipping-simulator' )
			),
		] );
	}

	protected function enqueue_scripts () {
		$suffix = h::get_defined( 'SCRIPT_DEBUG' ) ? '' : '.min';
		$plugin_version = h::config_get( 'VERSION' );
		wp_enqueue_script(
			h::prefix( 'form' ),
			h::plugin_url( "assets/js/form$suffix.js" ),
			[],
			$plugin_version,
			true
		);
		wp_enqueue_style(
			h::prefix( 'form' ),
			h::plugin_url( "assets/css/form$suffix.css" ),
			[],
			$plugin_version
		);

		do_action( 'wc_shipping_simulator_shortcode_enqueue_scripts' );
	}

	protected function get_customer_postcode () {
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			$billing_postcode = get_user_meta( $user_id, 'billing_postcode', true );
			$postcode = $billing_postcode ? $billing_postcode : get_user_meta( $user_id, 'shipping_postcode', true );
			return h::sanitize_postcode( $postcode );
		}
		return '';
	}
}
