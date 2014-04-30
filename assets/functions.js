jQuery(function ($) {
	function WippetsEditor () {};

	$.extend(WippetsEditor.prototype, {
		content_wrap: false,
		ace_editor_container: false,
		ace_editor: false,
		textarea: false,
		init: function () {
			this.textarea = $( '#content' );
			this.content_wrap = $( '#wp-content-wrap' );

			if ( this.textarea.length == 0 ) {
				return;
			};

			this.ace_editor_container = $('<div>', {
				'id': 'wippets_code_editor'
			}).insertBefore( this.textarea );

			this.initAce();

			this.switchEditorToAce();

			this.initSwitchEditorTabs();
		},
		initAce: function () {
			var self = this;

			this.ace_editor = ace.edit( this.ace_editor_container.get(0) );

			this.ace_editor.getSession().on( 'change', function () {
				self.textarea.val( self.ace_editor.getSession().getValue() );
			});
		},

		/* Editor Tabs */
		initSwitchEditorTabs: function () {
			var self = this;

			this.content_wrap.on('click', '.wp-switch-editor', function() {
				var $this = $(this);

				if ( $this.is('.switch-text') ) {
					self.switchEditorToText();
				} else if ( $this.is('.switch-ace') ) {
					self.switchEditorToAce();
				}
			});
		},
		switchEditorToAce: function () {
			this.content_wrap.removeClass('text-active').addClass('ace-active');

			this.ace_editor.setValue( this.textarea.get(0).value, -1 );
		},
		switchEditorToText: function () {
			this.content_wrap.removeClass('ace-active').addClass('text-active');

			this.textarea.val( this.ace_editor.getSession().getValue() );
		}
	});

	var editor = new WippetsEditor();
	editor.init();
});