<div class="wrap">
	<h2><?php echo get_admin_page_title() ?></h2>


	<form method="post" action="" id="sdr-settings-form">
		<?php wp_nonce_field( 'sdr_settings', 'sdr_settings' ); ?>
		
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="sdr_ace_theme">Color Theme</label>
					</th>
					<td>
						<select name="sdr_ace_theme" id="sdr_ace_theme" data-default="<?php echo esc_attr( $options['ace_theme'] ) ?>">
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
		</p>
	</form>

</div>


