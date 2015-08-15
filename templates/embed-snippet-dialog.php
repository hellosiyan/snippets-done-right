<div id="sdr-embed-dialog-backdrop" style="display: none"></div>
<div id="sdr-embed-dialog-wrap" style="display: none">
	<div id="sdr-embed-dialog-title">
		<?php _e('Embed Snippet'); ?>
		<button type="button" id="sdr-embed-dialog-close"><span class="screen-reader-text"><?php _e( 'Close' ); ?></span></button>
	</div>
	<form id="sdr-embed-snippet-form">
		<div id="sdr-embed-dialog-inner">
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
		</div>

		<div class="submitbox">
			<div id="sdr-embed-dialog-cancel">
				<a class="submitdelete deletion" href="#"><?php _e( 'Cancel' ); ?></a>
			</div>
			<div id="sdr-embed-dialog-insert">
				<input type="submit" name="submit" id="sdr-embed-dialog-submit" class="button button-primary" value="<?php echo esc_attr( __( 'Insert', 'snippets-done-right' ) ) ?>">
			</div>
		</div>
	</form>
</div>




