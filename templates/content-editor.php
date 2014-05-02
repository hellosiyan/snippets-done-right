<?php wp_nonce_field( 'wippets_snippet_editor', 'wippets_snippet_editor' ); ?>
<div class="edit-form-section">
	<div id="wp-content-wrap" class="wp-core-ui wp-editor-wrap">
		<input type="hidden" name="wippets_language" value="<?php echo esc_attr( $snippet_language ); ?>" />
		<div id="wp-content-editor-tools" class="wp-editor-tools hide-if-no-js">
			<div class="wp-editor-tabs">
				<a id="content-text" class="wp-switch-editor switch-text">Text</a>
				<a id="content-ace" class="wp-switch-editor switch-ace">Visual</a>
			</div>
		</div>
		<div id="wp-content-editor-container" class="wp-editor-container">
			<div id="ed_toolbar" class="wippets-toolbar"></div>
			<textarea class="wp-editor-area" name="content" id="content"><?php echo esc_textarea($post->post_content); ?></textarea>
		</div>
	</div>
	<table id="post-status-info"><tbody>
		<tr><td id="content-resize-handle" class="hide-if-no-js"><br></td></tr>
	</tbody></table>
</div>
