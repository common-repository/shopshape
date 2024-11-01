<?php
/**
 * Display addons.
 * @var array $with_children
 * @var array $without_children
 */
if ( ! defined( 'PHPArtisan\ShopShape\SLUG' ) ) {
	die;
}
?>

<div id="shopshape-addons">
	<?php if ( ! empty( $with_children ) ) : ?>
		<?php foreach ( $with_children as $name => $addon ) : ?>
			<details class="shopshape-addons-details" <?php echo array_keys( $with_children )[0] === $name ? 'open' : ''; ?>>
				<summary class="shopshape-addons-details-summary">
					<?php echo esc_html( $addon['name'] ); ?>
				</summary>
				<div class="shopshape-addons-details-content">
					<table class="shopshape-addons-radio-table">
						<tbody>
							<?php foreach ( $addon['children'] as $child ) : ?>
								<tr class="shopshape-addon-option-row">
									<th>

										<label>
											<input class="shopshape-addon-option-input" type="radio"
												name="selected_addon[<?php echo esc_attr( $addon['name'] ); ?>]"
												value="<?php echo esc_attr( $child['price'] ); ?>">
											<?php echo esc_html( $child['name'] ); ?>
										</label>
									</th>
									<td>
										<?php echo wc_price( $child['price'] ); // phpcs:ignore ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</details>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php if ( ! empty( $without_children ) ) : ?>
		<table class="shopshape-addons-checkbox-table">
			<tbody>
				<?php foreach ( $without_children as $name => $addon ) : ?>
					<tr class="shopshape-addon-option-row">
						<th>

							<label>
								<input class="shopshape-addon-option-input" type="checkbox"
									name="selected_addon[<?php echo esc_attr( $addon['name'] ); ?>]"
									value="<?php echo esc_attr( $addon['price'] ); ?>">
								<?php echo esc_html( $addon['name'] ); ?>
							</label>
						</th>
						<td>
							<?php echo wc_price( $addon['price'] ); // phpcs:ignore ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
	<div id="shopshape-addons-total">
		<table>
			<tr>
				<th>
					<?php esc_html_e( 'Total Addon Price', 'shopshape' ); ?>
				</th>
				<td id="shopshape-addons-total-price">
				</td>
			</tr>
		</table>
		<button id="shopshape-addons-clear" class="button">
			<?php esc_html_e( 'Clear', 'woocommerce' ); ?>
		</button>
	</div>
</div>
