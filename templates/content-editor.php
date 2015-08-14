<?php wp_nonce_field( 'sdr_snippet_editor', 'sdr_snippet_editor' ); ?>
<div class="edit-form-section">
	<div id="wp-content-wrap" class="wp-core-ui wp-editor-wrap">
		<input type="hidden" name="sdr_language" value="<?php echo esc_attr( $snippet_language ); ?>" />
		<div id="wp-content-editor-tools" class="wp-editor-tools hide-if-no-js">
			<div class="wp-editor-tabs">
				<a id="content-text" class="wp-switch-editor switch-text"><?php _e( 'Text', 'snippets-done-right' ) ?></a>
				<a id="content-ace" class="wp-switch-editor switch-ace"><?php _e( 'Visual', 'snippets-done-right' ) ?></a>
			</div>
		</div>
		<div id="wp-content-editor-container" class="wp-editor-container">
			<div id="ed_toolbar" class="sdr-toolbar"></div>
			<textarea class="wp-editor-area sdr-enabled-editor-area" name="content" id="content"><?php echo esc_textarea($post->post_content); ?></textarea>
		</div>
	</div>
</div>
