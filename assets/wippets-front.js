jQuery( function ( $ ) {
	var $document = $( document );
	var boxes = $('.wippets-snippet-container');

	if ( boxes.length === 0 ) {
		return;
	};

	function WippetsBox ( element ) {
		this.element = element;
		this.container = element.find( '.wippets-snippet-box' );
	};

	$.extend( WippetsBox.prototype, {
		init: function () {
			var language = this.element.data( 'language' );

			this.ace_editor = ace.edit( this.container.get( 0 ) );

			this.ace_editor.getSession().setMode( 'ace/mode/' + language );
			this.ace_editor.setShowFoldWidgets( false );
			this.ace_editor.setReadOnly( true );

			this.ace_editor.setOptions({
				maxLines: Infinity
			});
		}
	});

	/* Init all boxes */
	boxes.each( function () {
		wippets_box = new WippetsBox( $(this) );
		wippets_box.init();
	});
});