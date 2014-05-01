<?php

function wippets_enqueue_ace() {
	wp_enqueue_script('wippets-ace', '//cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ace.js');
	wp_enqueue_script('wippets-ace-modelist', '//cdnjs.cloudflare.com/ajax/libs/ace/1.1.3/ext-modelist.js');
}