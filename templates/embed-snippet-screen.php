<form id="sdr-embed-snippet-form">
	<p>
		<label for="sdr_snippet_id">Snippet</label>

		<select id="sdr_snippet_id" name="sdr_snippet_id">
			<option value="">Select Snippet</option>
			<?php foreach ($snippets as $snippet): ?>
				<option value="<?php echo $snippet->ID ?>"><?php echo esc_html( $snippet->post_title ) ?></option>
			<?php endforeach ?>
		</select>
	</p>

	<p>
		<label for="sdr_height">Height</label>
		<input type="number" step="1" min="0" id="sdr_height" name="sdr_height" value="0" /> lines<br/>
		<em>Enter <code>0</code> to show all lines</em>
	</p>

	<p>
		<label for="sdr_show_line_numbers">
			<input name="sdr_show_line_numbers" type="checkbox" id="sdr_show_line_numbers" value="1" checked="checked">
			Show line numbers
		</label>
	</p>

	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button " value="Insert">
	</p>
</form>