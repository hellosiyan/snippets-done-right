<form id="wippets-embed-snippet-form">
	<label for="wippet_snippet_id">Snippet</label>

	<select id="wippet_snippet_id" name="wippet_snippet_id">
		<option value="">Select Snippet</option>
		<?php foreach ($snippets as $snippet): ?>
			<option value="<?php echo $snippet->ID ?>"><?php echo esc_html( $snippet->post_title ) ?></option>
		<?php endforeach ?>
	</select>


	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button " value="Insert">
	</p>
</form>