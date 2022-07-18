<?php use Shipping_Simulator\Helpers as h; ?>

<?php if ( count( $rates ) ) : ?>
	<?php do_action( 'wc_shipping_simulator_results_before', $data ) ?>

	<table>
		<?php do_action( 'wc_shipping_simulator_results_start', $rates ) ?>

		<?php foreach ( $rates as $rate ) : ?>
			<tr class="shipping-rate-method-<?php echo esc_attr( $rate->get_method_id() ) ?>">
				<th class="col-label">
					<span class="shipping-rate-label"><?php echo h::safe_html( $rate->get_label() ); ?></span>
					<?php do_action( 'wc_shipping_simulator_results_col_label', $rate ) ?>
				</th>
				<td  class="col-cost">
					<?php echo wc_price( $rate->get_cost() ); ?>
					<?php do_action( 'wc_shipping_simulator_results_col_cost', $rate ) ?>
				</td>
			</tr>
		<?php endforeach; ?>

		<?php do_action( 'wc_shipping_simulator_results_end', $rates ) ?>
	</table>

	<?php do_action( 'wc_shipping_simulator_results_after', $data ) ?>
<?php elseif ( $no_results_notice ) : ?>

	<div class="no-results"><?php echo h::safe_html( $no_results_notice ) ?></div>

<?php endif ?>

