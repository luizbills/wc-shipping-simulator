<?php

namespace Shipping_Simulator\Integration;

use Shipping_Simulator\Helpers as h;

final class Brazil {
	protected $state_list = null;
	protected static $instace = null;

	public static function instance () {
		return self::$instace;
	}

	public function __start () {
		self::$instace = $this;
		add_action( 'wc_shipping_simulator_load_integrations', [ $this, 'add_hooks' ] );
	}

	public function add_hooks () {
		$enabled = apply_filters( 'wc_shipping_simulator_integration_brazil_enabled', true );
		if ( $enabled ) {
			add_filter( 'wc_shipping_simulator_form_input_mask', [ $this, 'form_input_mask' ] );

			add_action( 'wc_shipping_simulator_form_end', [ $this, 'form_end' ] );

			add_filter( 'wc_shipping_simulator_sanitize_request_data', [ $this, 'sanitize_request_data' ], 10, 2 );

			add_action( 'wc_shipping_simulator_validate_request_data', [ $this, 'validate_request_data' ] );

			add_filter( 'wc_shipping_simulator_request_update_package', [ $this, 'update_package' ], 10, 2 );
		}
	}

	public function sanitize_request_data ( $sanitized, $posted ) {
		$sanitized['country'] = h::get( $posted['country'], 'BR' );
		return $sanitized;
	}

	public function validate_request_data ( $posted ) {
		if ( 'BR' !== h::get( $posted['country'] ) ) return;

		h::throw_if(
			! $this->is_cep( $posted['postcode'] ),
			'O CEP informado não tem 8 digitos.'
		);

		h::throw_if(
			null === $this->get_state_by_postcode( $posted['postcode'] ),
			'O CEP informado não existe.'
		);
	}

	public function update_package ( $package, $posted ) {
		if ( 'BR' === h::get( $posted['country'] ) ) {
			$package->set_destination( [
				'country' => 'BR',
				'state' => $this->get_state_by_postcode( $posted['postcode'] )
			] );
		}
		return $package;
	}

	public function form_input_mask ( $mask ) {
		return 'XXXXX-XXX';
	}

	public function form_end () {
		$cep_finder_link = apply_filters(
			'wc_shipping_simulator_brazil_cep_finder_link',
			'https://buscacepinter.correios.com.br/app/endereco/'
		);
		$cep_finder_label = apply_filters(
			'wc_shipping_simulator_brazil_cep_finder_label',
			'Não sei meu cep'
		);
		?>
		<div id="wc-shipping-sim-br-cep-finder">
			<a href="<?= esc_url( $cep_finder_link ) ?>"><?= esc_html( $cep_finder_label ) ?></a>
		</div>
		<?php
	}

	protected function is_cep ( $postcode ) {
		return h::str_length( $postcode ) === 8;
	}

	protected function get_state_by_postcode ( $postcode ) {
		$postcode = (int) $postcode;
		$result = null;

		foreach ( $this->get_states_postcode_range() as $state ) {
			$min = (int) $state['min'];
			$max = (int) $state['max'];
			if ( $postcode >= $min && $postcode <= $max ) {
				$result = $state['name'];
				break;
			}
		}

		return $result;
	}

	protected function get_states_postcode_range () {
		if ( $this->state_list ) return $this->state_list;

		$json = '[{"name":"SP","min":"01000000","max":"19999999"},{"name":"RJ","min":"20000000","max":"28999999"},{"name":"ES","min":"29000000","max":"29999999"},{"name":"MG","min":"30000000","max":"39999999"},{"name":"BA","min":"40000000","max":"48999999"},{"name":"SE","min":"49000000","max":"49999999"},{"name":"PE","min":"50000000","max":"56999999"},{"name":"AL","min":"57000000","max":"57999999"},{"name":"PB","min":"58000000","max":"58999999"},{"name":"RN","min":"59000000","max":"59999999"},{"name":"CE","min":"60000000","max":"63999999"},{"name":"PI","min":"64000000","max":"64999999"},{"name":"MA","min":"65000000","max":"65999999"},{"name":"PA","min":"66000000","max":"68899999"},{"name":"AP","min":"68900000","max":"68999999"},{"name":"AM","min":"69000000","max":"69899999"},{"name":"RR","min":"69300000","max":"69399999"},{"name":"AC","min":"69900000","max":"69999999"},{"name":"DF","min":"70000000","max":"73699999"},{"name":"GO","min":"72800000","max":"76799999"},{"name":"TO","min":"77000000","max":"77999999"},{"name":"MT","min":"78000000","max":"78899999"},{"name":"RO","min":"76800000","max":"76999999"},{"name":"MS","min":"79000000","max":"79999999"},{"name":"PR","min":"80000000","max":"87999999"},{"name":"SC","min":"88000000","max":"89999999"},{"name":"RS","min":"90000000","max":"99999999"}]';
		$this->state_list = json_decode( $json, true );

		return $this->state_list;
	}
}
