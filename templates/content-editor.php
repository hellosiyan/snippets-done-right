<div class="edit-form-section">
	<div id="wp-content-wrap" class="wp-core-ui wp-editor-wrap">
		<div id="wp-content-editor-tools" class="wp-editor-tools hide-if-no-js">
			<div class="wp-editor-tabs">
				<a id="content-text" class="wp-switch-editor switch-text">Text</a>
				<a id="content-ace" class="wp-switch-editor switch-ace">Visual</a>
			</div>
		</div>
		<div id="wp-content-editor-container" class="wp-editor-container">
			<textarea class="wp-editor-area" name="content" id="content"><?php echo esc_textarea($post->post_content); ?></textarea>
		</div>
	</div>
</div>
