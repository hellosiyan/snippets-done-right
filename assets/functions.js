jQuery(function ($) {
	function WippetsEditor () {};

	$.extend(WippetsEditor.prototype, {
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
			this.initToolbar();

			this.switchEditorToAce();

			this.initSwitchEditorTabs();
		},
		initAce: function () {
			var self = this;

			this.ace_editor = ace.edit( this.ace_editor_container.get(0) );

			this.setMode( this.getMode() );

			this.ace_editor.getSession().on( 'change', function () {
				self.textarea.val( self.ace_editor.getSession().getValue() );
			});
		},
		setMode: function ( mode ) {
			if ( ! mode ) {
				return;
			};

			this.ace_editor.getSession().setMode('ace/mode/' + mode);
			this.content_wrap.find( 'input[name=wippets_language]' ).val( mode );
		},
		getMode: function () {
			return this.content_wrap.find( 'input[name=wippets_language]' ).val();
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
		},

		/* Editor Toolbar */
		initToolbar: function () {
			this.toolbar = {
				element: this.content_wrap.find( '.wippets-toolbar' )
			};

			this.initModeSelector();
		},
		initModeSelector: function () {
			var self = this;
			var modelist = ace.require( 'ace/ext/modelist' );
			var current_mode = this.getMode();

			this.toolbar.language_selector = $( '<select>', {
				name: 'wippets_language'
			});

			$( '<option>', {
				text: 'Plain Text',
				value: 'text'
			}).appendTo( this.toolbar.language_selector );

			for (var i = 0; i < modelist.modes.length; i++) {
				if ( modelist.modes[i].name === 'text' ) {
					continue;
				};

				$('<option>', {
					text: modelist.modes[i].caption,
					value: modelist.modes[i].name
				}).appendTo( this.toolbar.language_selector );
			};

			this.toolbar.language_selector.val( current_mode );

			this.toolbar.language_selector.on( 'change', function() {
				self.setMode( this.value );
			});

			this.toolbar.language_selector.appendTo( this.toolbar.element );
		}
	});

	var editor = new WippetsEditor();
	editor.init();
});