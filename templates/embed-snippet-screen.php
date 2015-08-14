<form id="sdr-embed-snippet-form">
	<p>
		<label for="sdr_snippet_id"><?php _e( 'Snippet', 'snippets-done-right' ) ?></label>

		<select id="sdr_snippet_id" name="sdr_snippet_id">
			<option value=""><?php _e( 'Select Snippet', 'snippets-done-right' ) ?></option>
			<?php foreach ($snippets as $snippet): ?>
				<option value="<?php echo $snippet->ID ?>"><?php echo esc_html( $snippet->post_title ) ?></option>
			<?php endforeach ?>
		</select>
	</p>

	<p>
		<label for="sdr_height"><?php _e( 'Height', 'snippets-done-right' ) ?></label>
		<input type="number" step="1" min="0" id="sdr_height" name="sdr_height" value="0" /> <?php _e( 'lines', 'snippets-done-right' ) ?><br/>
		<em><?php _e( 'Enter <code>0</code> to show all lines', 'snippets-done-right' ) ?></em>
	</p>

	<p>
		<label for="sdr_show_line_numbers">
			<input name="sdr_show_line_numbers" type="checkbox" id="sdr_show_line_numbers" value="1" checked="checked">
			<?php _e( 'Show line numbers', 'snippets-done-right' ) ?>
		</label>
	</p>

	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button " value="<?php echo esc_attr( __( 'Insert', 'snippets-done-right' ) ) ?>">
	</p>
</form>