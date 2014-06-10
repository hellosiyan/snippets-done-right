<?php

function sdr_enqueue_ace() {
	wp_enqueue_script('sdr-ace', SDR_URL . '/assets/ace/src-min-noconflict/ace.js', null, null, true);
	wp_enqueue_script('sdr-ace-modelist', SDR_URL . '/assets/ace/src-min-noconflict/ext-modelist.js', array( 'sdr-ace' ), null, true);
	wp_enqueue_script('sdr-ace-themelist', SDR_URL . '/assets/ace/src-min-noconflict/ext-themelist.js', array( 'sdr-ace' ), null, true);
}