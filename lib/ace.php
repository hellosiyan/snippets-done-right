<?php

function sdr_enqueue_ace() {
	wp_enqueue_script('sdr-ace', '//cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js', null, null, true);
	wp_enqueue_script('sdr-ace-modelist', '//cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ext-modelist.js', array( 'sdr-ace' ), null, true);
	wp_enqueue_script('sdr-ace-themelist', '//cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ext-themelist.js', array( 'sdr-ace' ), null, true);
}