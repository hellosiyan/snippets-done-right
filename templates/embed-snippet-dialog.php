<div id="sdr-embed-dialog-backdrop" style="display: none"></div>
<div id="sdr-embed-dialog-wrap" style="display: none">
	<div id="sdr-embed-dialog-title">
		<?php _e('Embed Snippet'); ?>
		<button type="button" id="sdr-embed-dialog-close"><span class="screen-reader-text"><?php _e( 'Close' ); ?></span></button>
	</div>
	<form id="sdr-embed-snippet-form">
		<div id="sdr-embed-dialog-inner">
			<p>
				<label><?php _e( 'Snippet', 'snippets-done-right' ) ?></label>

				<div id="sdr-snippets-list">
					<ul>
						<?php foreach ($snippets as $snippet): ?>
							<li>
								<input type="radio" name="sdr_snippet_id" class="item-permalink" value="<?php echo $snippet->ID ?>">
								<span class="item-title"><?php echo esc_html( $snippet->post_title ) ?></span>
								<span class="item-info"><?php echo esc_html( sdr_snippet_get_language( $snippet->ID ) ) ?></span>
							</li>
						<?php endforeach ?>
					</ul>
				</div>
			</p>

			<p>
				<label for="sdr_show_line_numbers" class="inline">
					<input name="sdr_show_line_numbers" type="checkbox" id="sdr_show_line_numbers" value="1" checked="checked">
					<?php _e( 'Show line numbers', 'snippets-done-right' ) ?>
				</label>
			</p>

			<p>
				<?php _e( 'Visible lines:', 'snippets-done-right' ); ?>
				<input type="number" step="1" min="0" id="sdr_height" name="sdr_height" value="0" />
				<em>(<?php _e( 'Enter <code>0</code> to show all lines', 'snippets-done-right' ) ?>)</em>
			</p>
		</div>

		<div class="submitbox">
			<div id="sdr-embed-dialog-cancel">
				<a class="submitdelete deletion" href="#"><?php _e( 'Cancel' ); ?></a>
			</div>
			<div id="sdr-embed-dialog-insert">
				<input type="submit" name="submit" id="sdr-embed-dialog-submit" class="button button-primary disabled" value="<?php echo esc_attr__( 'Insert', 'snippets-done-right' ) ?>">
			</div>
		</div>
	</form>
</div>




