<?php

use Shipping_Simulator\Shortcode;

$prefix = self::get_prefix();
$shortcode = Shortcode::get_tag();

return [
	[
		'id' => $prefix . 'settings',
		'type' => 'title',
		'name' => esc_html__( 'Shipping Simulator Settings', 'wc-shipping-simulator' ),
		'desc' => esc_html__( 'The following options are used to configure the Shipping Simulator. In almost all text fields below you can use html tags like <span>, <strong>, <em>, etc.', 'wc-shipping-simulator' ),
	],
	[
		'id'       => $prefix . 'auto_insert',
		'type'     => 'checkbox',
		'name'     => esc_html__( 'Enable Auto-Insert', 'wc-shipping-simulator' ),
		'desc'     => esc_html__( 'Enable', 'wc-shipping-simulator' ),
		'desc_tip' => sprintf(
			// translators: %s is a shortcode tag
			esc_html__( 'Display automatically the shipping simulator in product pages. Alternatively you can manually insert the shipping simulator using the %s shortcode.', 'wc-shipping-simulator' ),
			'<code>' . "[$shortcode]" . '</code>'
		),
		'default'  => 'yes'
	],
	[
		'id'       => $prefix . 'requires_variation',
		'type'     => 'checkbox',
		'name'     => esc_html__( 'Product variation is required', 'wc-shipping-simulator' ),
		'desc'     => esc_html__( 'Enable', 'wc-shipping-simulator' ),
		'desc_tip' => esc_html__( 'Disable this option to allow customers simulate shipping rates even when a variation is not selected on variable products. However, always make sure that the variable product has a defined weight.', 'wc-shipping-simulator' ),
		'default'  => 'yes'
	],
	[
		'id'       => $prefix . 'form_title',
		'type'     => 'text',
		'name'     => esc_html__( 'Title', 'wc-shipping-simulator' ),
		'desc'     => esc_html__( 'Text that appears before the simulator fields.', 'wc-shipping-simulator' ),
		'default'  => __( 'Check shipping cost and delivery time:', 'wc-shipping-simulator' ),
	],
	[
		'id'       => $prefix . 'input_placeholder',
		'type'     => 'text',
		'name'     => esc_html__( 'Input placeholder', 'wc-shipping-simulator' ),
		'desc'     => esc_html__( 'Text that appears when the postcode field is empty.', 'wc-shipping-simulator' ),
		'default'  => __( 'Type your postcode', 'wc-shipping-simulator' ),
	],
	[
		'id'       => $prefix . 'submit_label',
		'type'     => 'text',
		'name'     => esc_html__( 'Button Text', 'wc-shipping-simulator' ),
		'desc'     => esc_html__( 'Text that appears on the shipping simulator button.', 'wc-shipping-simulator' ),
		'default'  => __( 'Apply', 'wc-shipping-simulator' ),
	],
	[
		'id'       => $prefix . 'after_results',
		'type'     => 'textarea',
		'name'     => esc_html__( 'Text after results.', 'wc-shipping-simulator' ),
		'default'  => __( 'Delivery times start from the confirmation of payment.', 'wc-shipping-simulator' ),
		'css' => 'height: 6rem',
	],
	[
		'id'       => $prefix . 'no_results',
		'type'     => 'textarea',
		'name'     => esc_html__( 'Text when not have results.', 'wc-shipping-simulator' ),
		'default'  => __( 'Unfortunately at this moment this product cannot be delivered to the specified region.', 'wc-shipping-simulator' ),
		'css' => 'height: 6rem;',
	],
	[
		'id'       => $prefix . 'debug_mode',
		'type'     => 'checkbox',
		'name'     => esc_html__( 'Debug mode', 'wc-shipping-simulator' ),
		'desc'     => esc_html__( 'Enable', 'wc-shipping-simulator' ),
		'desc_tip' => __( 'Enable debug mode to log your shipping simulations and display helpful tips.', 'wc-shipping-simulator' ),
		'default'  => 'no'
	],
	[
		'id' => $prefix . 'settings',
		'type' => 'sectionend',
	],
];
